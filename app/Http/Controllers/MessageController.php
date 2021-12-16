<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Enums\Role as EnumRole;
use Silber\Bouncer\Database\Role as UserRole;
use App\Enums\MessageType;
use BenSampo\Enum\Rules\EnumValue;
use Datatables;
use Carbon\Carbon;
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
                $btn .= '<button type="button" id="copyMessage" name="copyMessage" class="btn btn-outline-primary btn-sm m-2" data-msg-id="' . $data->id . '"
                  ><i class="fas fa-copy"></i></button>';
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
        $user_scopetype = UserRole::all()->pluck('name','id');
        return view('message/message_new', ['user_scopetype'=>$user_scopetype, 'scopetype' => EnumRole::getInstances(), 'user' => $user, 'region' => $region]);
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
            'title' => 'required|string|max:60',
            'body' => 'required|string',
            'greeting' => 'required|string|max:40',
            'salutation' => 'required|string|max:40',
            'send_at' => 'required|date|after:today',
            'to_members' => 'required_without:to_users',
            'to_members.*' => [new EnumValue(EnumRole::class, false)],
            'cc_members.*' => [new EnumValue(EnumRole::class, false)],
            'to_users' => 'required_without:to_members|nullable',
            'to_users.*' => 'required|exists:roles,id',

        ]);
        Log::info('message form data validated OK.');

        $msg = new Message($data);
        $msg->user()->associate($user);
        $msg->region()->associate($region);
        $msg->save();
        Log::notice('new message created.', ['message-id'=>$msg->id]);

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
        $user_scopetype = UserRole::all()->pluck('name','id');

        return view('message/message_edit', ['message' => $message, 'scopetype' => EnumRole::getInstances(), 'user_scopetype'=>$user_scopetype]);
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
            'title' => 'required|string|max:60',
            'body' => 'required|string',
            'greeting' => 'required|string|max:40',
            'salutation' => 'required|string|max:40',
            'send_at' => 'date|after:today',
            'to_members' => 'required_without:to_users',
            'to_members.*' => [new EnumValue(EnumRole::class, false)],
            'cc_members.*' => [new EnumValue(EnumRole::class, false)],
            'to_users' => 'required_without:to_members|nullable',
            'to_users.*' => 'sometimes|exists:roles,id',
        ]);
        Log::info('message form data validated OK.');
        if ( ! isset($data['to_members']) ){
            $data['to_members'] = null;
        }
        if ( ! isset($data['cc_members']) ){
            $data['cc_members'] = null;
        }
        if ( ! isset($data['to_users']) ){
            $data['to_users'] = null;
        }

        $message->update( $data );
        Log::notice('message updated.', ['message-id'=> $message->id]);


        return redirect()->route('message.index', ['language' => app()->getLocale(), 'region' => $message->region, 'user' => $message->user]);
    }

    /**
     * Mark a message as read
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   DatabaseNotification $message
     * @return \Illuminate\Http\Response
     */
    public function mark_as_read(DatabaseNotification $message)
    {
       $message->markAsRead();
       return back();
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
     * duplicate a message
     *
     * @param  $language
     * @param  \App\Models\Message  $message
     */
    public function copy($language, Message $message)
    {
        Log::info('preparing to duplicate message.', ['message->id' => $message->id]);
        $new_msg = $message->replicate();
        $new_msg->sent_at = null;
        $new_msg->send_at = now()->addDays(8);
        $new_msg->save();

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
        $message->delete();
        Log::notice('message deleted',['message-id'=>$message->id]);

        return redirect()->route('message.index', ['language' => app()->getLocale(), 'region' => $message->region, 'user' => $message->user]);
    }
}
