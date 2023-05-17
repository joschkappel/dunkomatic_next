<?php

namespace App\Http\Controllers;

use App\Enums\Role as EnumRole;
use App\Jobs\SendCustomMessage;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\Region;
use App\Models\User;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Silber\Bouncer\Database\Role as UserRole;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function index($language, Region $region, User $user)
    {
        Log::info('showing message list.');

        return view('message/message_list', ['language' => $language, 'user' => $user, 'region' => $region]);
    }

    /**
     * databales.net listing of messages for the auth user
     *
     * @param  string  $language
     * @param  Region  $region
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable_user(string $language, Region $region, User $user)
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
                if ($data->sent_at != null) {
                    $btn .= '<button type="button" id="showMessage" name="showMessage" class="btn btn-outline-primary btn-sm m-2" data-msg-id="' . $data->id . '"
                    data-msg-greeting="' . $data->greeting . '" data-msg-attachment="' . $data->attachment_filename . '" data-msg-subject="' . $data->title . '"  data-msg-body="' . htmlentities($data->body) . '"><i class="far fa-eye"></i></button>';
                }

                return $btn;
            })
            ->addColumn('action_send', function ($data) {
                if ((($data->send_at == null) or ($data->send_at > now())) and ($data->sent_at == null)) {
                    $btn = '<button type="button" id="sendMessage" name="sendMessage" class="btn btn-outline-success btn-sm" data-msg-id="' . $data->id . '"
               data-msg-title="' . $data->title . '"><i class="far fa-paper-plane"></i></button>';

                    return $btn;
                }
            })
            ->editColumn('title', function ($msg) use ($language) {
                if ((isset($msg->sent_at) and ($msg->sent_at) < now())) {
                    return $msg['title'];
                } else {
                    return '<a href="' . route('message.edit', ['language' => $language, 'message' => $msg->id]) . '">' . $msg->title . ' <i class="fas fa-arrow-circle-right"></i></a>';
                }
            })
            ->editColumn('delete_at', function ($msg) use ($language) {
                return ($msg->delete_at == null) ? null : Carbon::parse($msg->delete_at)->locale($language)->isoFormat('L');
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
     * @param  string  $language
     * @param  \App\Models\Region  $region
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function create($language, Region $region, User $user)
    {
        Log::info('create new message');
        $user_scopetype = UserRole::all()->pluck('name', 'id');

        return view('message/message_new', ['user_scopetype' => $user_scopetype, 'scopetype' => EnumRole::getInstances(), 'user' => $user, 'region' => $region]);
    }

    /**
     * show resource
     *
     * @param  string  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $notification)
    {
        $raw_note = DB::table('notifications')->where('id', $notification)->first();

        return $raw_note->data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Region $region, User $user)
    {
        Log::debug('raw request', ['request' => $request->input()]);
        $data = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|string',
            'greeting' => 'required|string|max:40',
            'salutation' => 'required|string|max:40',
            'send_at' => 'required|date|after:today',
            'delete_at' => 'required|date|after:send_at',
            'to_members' => 'required_without:notify_users',
            'to_members.*' => [new EnumValue(EnumRole::class, false)],
            'cc_members.*' => [new EnumValue(EnumRole::class, false)],
            'notify_users' => 'sometimes|required|boolean',
            'attachfiles' => 'array|max:3',
            'attachfiles.*' => 'max:' . config('dunkomatic.mail_attachment_size') . '|mimes:pdf,xlsx',
        ]);

        // Log::debug(print_r($request->all(), true));
        // $fname = $request->attachfile->getClientOriginalName();
        // Log::debug($fname);

        Log::info('message form data validated OK.');

        if (!$request->has('notify_users')) {
            $data['notify_users'] = false;
        }

        $msg = new Message($data);
        $msg->user()->associate($user);
        $msg->region()->associate($region);
        $msg->save();
        Log::notice('new message created.', ['message-id' => $msg->id]);

        //( save attachment)
        foreach ($data['attachfiles'] as $afile) {
            $filename = $afile->getClientOriginalName();
            $location = $afile->store('message_attachments');
            $attachment = new MessageAttachment(['filename' => $filename, 'location' => $location]);
            $msg->message_attachments()->save($attachment);
        }

        return redirect()->route('message.index', ['language' => app()->getLocale(), 'user' => $user, 'region' => $region]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $language
     * @param  \App\Models\Message  $message
     * @return \Illuminate\View\View
     */
    public function edit($language, Message $message)
    {
        Log::debug('editing message.', ['message-id' => $message->id]);
        $user_scopetype = UserRole::all()->pluck('name', 'id');

        return view('message/message_edit', ['message' => $message, 'scopetype' => EnumRole::getInstances(), 'user_scopetype' => $user_scopetype]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Message $message)
    {
        $data = $request->validate([
            'title' => 'required|string|max:60',
            'body' => 'required|string',
            'greeting' => 'required|string|max:40',
            'salutation' => 'required|string|max:40',
            'send_at' => 'date|after:today',
            'delete_at' => 'date|after:send_at',
            'to_members' => 'required_without:notify_users',
            'to_members.*' => [new EnumValue(EnumRole::class, false)],
            'cc_members.*' => [new EnumValue(EnumRole::class, false)],
            'notify_users' => 'sometimes|required|boolean',
            'attachfile' => 'sometimes|max:' . config('dunkomatic.mail_attachment_size') . '|mimes:pdf',
        ]);
        Log::info('message form data validated OK.');
        //( save attachment)
        if ($request->has('attachfile')) {
            $data['attachment_filename'] = $request->attachfile->getClientOriginalName();
            $data['attachment_location'] = $request->attachfile->store('message_attachments');
            unset($data['attachfile']);
        } else {
            $data['attachment_filename'] = null;
            $data['attachment_location'] = null;
        }
        if (!$request->has('notify_users')) {
            $data['notify_users'] = false;
        }
        if (!$request->has('to_members')) {
            $data['to_members'] = null;
        }
        if (!$request->has('cc_members')) {
            $data['cc_members'] = null;
        }

        $message->update($data);
        Log::notice('message updated.', ['message-id' => $message->id]);

        return redirect()->route('message.index', ['language' => app()->getLocale(), 'region' => $message->region, 'user' => $message->user]);
    }

    /**
     * Mark a message as read
     *
     * @param  DatabaseNotification  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function mark_as_read(DatabaseNotification $message)
    {
        $message->markAsRead();

        return back();
    }

    /**
     * sedn notification for this message
     *
     * @param  string  $language
     * @param  Message  $message
     * @return bool
     */
    public function send($language, Message $message)
    {
        Log::info('prepare notification for message.', ['message->id' => $message->id]);

        SendCustomMessage::dispatchSync($message);
        //->delay(now()->addMinutes(1));

        return true;
    }

    /**
     * duplicate a message
     *
     * @param  string  $language
     * @param  Message  $message
     * @return bool
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

    public function get_attachment(Message $message)
    {
        Log::info('download request for message attachment', ['filename' => $message->attachment_filename]);
        Storage::disk('public')->writeStream(
            $message->attachment_filename,
            Storage::readStream($message->attachment_location)
        );
        return response()->file(Storage::disk('public')->path($message->attachment_filename));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Message $message)
    {
        if ($message->attachment_filename != null) {
            Storage::delete($message->attachment_location);
        }
        $message->delete();
        Log::notice('message deleted', ['message-id' => $message->id]);

        return redirect()->route('message.index', ['language' => app()->getLocale(), 'region' => $message->region, 'user' => $message->user]);
    }
}
