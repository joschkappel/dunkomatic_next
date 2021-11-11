<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Enums\Role;
use App\Enums\MessageType;
use BenSampo\Enum\Rules\EnumValue;
use Datatables;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

use App\Jobs\ProcessCustomMessages;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($language, Region $region, User $user)
    {
        Log::info('showing message list.');
        return view('message/message_list', ['language' => $language, 'user' => $user, 'region' => $region]);
    }


    /**
     * Display a listing of message for the auth user
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable_user($language, Region $region, User $user)
    {
        $msgs = $user->region_messages($region->id)->orderBy('updated_at', 'ASC')->get();

        Log::info('preparing message list');
        $msglist = datatables()::of($msgs);

        return $msglist
            ->rawColumns(['send_at', 'sent_at', 'action_send', 'action', 'title', 'body'])
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $btn = '<button type="button" id="deleteMessage" name="deleteMessage" class="btn btn-outline-danger btn-sm" data-msg-id="' . $data->id . '"
                  data-msg-title="' . $data->title . '" data-toggle="modal" data-target="#modalDeleteMessage"><i class="fa fa-trash"></i></button>';
                return $btn;
            })
            ->addColumn('action_send', function ($data) {
                if ((($data->send_at == null) or ($data->send_at > now())) and ($data->sent_at == null)) {
                    $btn = '<button type="button" id="sendMessage" name="sendMessage" class="btn btn-outline-success btn-sm" data-msg-id="' . $data->id . '"
               data-msg-title="' . $data->title . '"><i class="far fa-paper-plane"></i></button>';
                    return $btn;
                };
            })
            ->editColumn('title', function ($msg) use ($language) {
                if ((isset($msg->sent_at) and ($msg->sent_at) < now())) {
                    return $msg->title;
                } else {
                    return '<a href="' . route('message.edit', ['language' => $language, 'message' => $msg->id]) . '">' . $msg->title . ' <i class="fas fa-arrow-circle-right"></i></a>';
                }
            })
            ->editColumn('updated_at', function ($msg) use ($language) {
                return Carbon::parse($msg->updated_at)->locale($language)->isoFormat('LLL');
            })
            ->editColumn('send_at', function ($msg) use ($language) {
                return ($msg->send_at == null) ? null : Carbon::parse($msg->send_at)->locale($language)->isoFormat('L');
            })
            ->editColumn('sent_at', function ($msg) use ($language) {
                return ($msg->sent_at == null) ? null : Carbon::parse($msg->sent_at)->locale($language)->isoFormat('L');
            })
            ->editColumn('body', function ($msg) {
                return  Str::substr($msg->body, 0, 20);
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($language, Region $region, User $user)
    {
        Log::info('create new message');
        return view('message/message_new', ['scopetype' => Role::getInstances(), 'user' => $user, 'region' => $region]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Region $region, User $user)
    {
        $data = $request->validate([
            'title' => 'required|string|max:40',
            'body' => 'required|string',
            'greeting' => 'required|string|max:40',
            'salutation' => 'required|string|max:40',
            'send_at' => 'required|date|after:today',
            'dest_to.*' => ['required', new EnumValue(Role::class, false)],
            'dest_cc.*' => [new EnumValue(Role::class, false)],
        ]);
        Log::info('message form data validated OK.');

        $msg = new Message();
        $msg->user()->associate($user);
        $msg->region()->associate($region);
        $msg->title = $data['title'];
        $msg->greeting = $data['greeting'];
        $msg->body = $data['body'];
        $msg->salutation = $data['salutation'];
        $msg->send_at = $data['send_at'];
        $msg->save();
        Log::notice('new message created.', ['message-id'=>$msg->id]);

        foreach ($data['dest_to'] as $d) {
            $dest = $msg->message_destinations()->create([
                'role_id' => Role::coerce(intval($d)),
                'type' => MessageType::to(),
            ]);
            Log::notice('new message TO destination created.', ['messagedest-id'=>$dest->id]);
        }
        foreach ($data['dest_cc'] as $d) {
            $dest = $msg->message_destinations()->create([
                'role_id' => Role::coerce(intval($d)),
                'type' => MessageType::cc(),
            ]);
            Log::notice('new message CC destination created.', ['messagedest-id'=>$dest->id]);
        }

        return redirect()->route('message.index', ['language' => app()->getLocale(), 'user' => $user, 'region' => $region]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Message $message)
    {
        Log::debug('editing message.', ['message-id' => $message->id]);
        $dest_to = $message->message_destinations()->where('type', new MessageType(MessageType::to))->get()->pluck('role_id');
        $dest_cc = $message->message_destinations()->where('type', new MessageType(MessageType::cc))->get()->pluck('role_id');

        $data = array();
        $data['message'] = $message;
        $data['dest_to'] = $dest_to;
        $data['dest_cc'] = $dest_cc;

        return view('message/message_edit', ['message' => $data, 'scopetype' => Role::getInstances()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {

        $data = $request->validate([
            'title' => 'required|string|max:40',
            'body' => 'required|string',
            'greeting' => 'required|string|max:40',
            'salutation' => 'required|string|max:40',
            'send_at' => 'date|after:today',
            'dest_to.*' => ['required', new EnumValue(Role::class, false)],
            'dest_cc.*' => [new EnumValue(Role::class, false)],
        ]);
        Log::info('message form data validated OK.');

        $message->title = $data['title'];
        $message->greeting = $data['greeting'];
        $message->body = $data['body'];
        $message->salutation = $data['salutation'];
        $message->send_at = $data['send_at'];

        $message->save();
        Log::notice('message updated.', ['message-id'=> $message->id]);

        // delete old destintaion
        $message->message_destinations()->delete();

        foreach ($data['dest_to'] as $d) {
            $dest = $message->message_destinations()->create([
                'role_id' => Role::coerce(intval($d)),
                'type' => MessageType::to(),
            ]);
            Log::notice('new message TO destination created.', ['messagedest-id'=>$dest->id]);
        }
        if (isset($data['dest_cc'])) {
            foreach ($data['dest_cc'] as $d) {
                $dest = $message->message_destinations()->create([
                    'role_id' => Role::coerce(intval($d)),
                    'type' => MessageType::cc(),
                ]);
                Log::notice('new message CC destination created.', ['messagedest-id'=>$dest->id]);
            }
        }

        return redirect()->route('message.index', ['language' => app()->getLocale(), 'region' => $message->region, 'user' => $message->user]);
    }

    /**
     * sedn notification for this message
     *
     * @param  $language
     * @param  \App\Models\Message  $message
     */
    public function send($language, Message $message)
    {
        Log::info('prepare notification for message.', ['message->id' => $message->id]);

        ProcessCustomMessages::dispatchSync($message);
        //->delay(now()->addMinutes(1));

        return true;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $message->message_destinations()->delete();
        Log::info('message destintations deleted',['message-id'=>$message->id]);

        $message->delete();
        Log::notice('message deleted',['message-id'=>$message->id]);

        return redirect()->route('message.index', ['language' => app()->getLocale(), 'region' => $message->region, 'user' => $message->user]);
    }
}
