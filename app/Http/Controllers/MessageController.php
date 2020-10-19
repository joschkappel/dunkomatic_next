<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;
use App\Enums\MessageType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Database\Eloquent\Builder;
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
    public function index()
    {
      return view('message/message_list');
    }


    /**
     * Display a listing of message for the auth user
     *
     * @return \Illuminate\Http\Response
     */
    public function list_user_dt($language, User $user)
    {
      $msgs = $user->messages()->orderBy('updated_at','ASC')->get();

      $msglist = datatables::of($msgs);

      return $msglist
        ->rawColumns(['send_at','sent_at', 'action_send', 'action','title','body'])
        ->addIndexColumn()
        ->addColumn('action', function($data){
               $btn = '<button type="button" id="deleteMessage" name="deleteMessage" class="btn btn-outline-danger btn-sm" data-msg-id="'.$data->id.'"
                  data-msg-title="'.$data->title.'" data-toggle="modal" data-target="#modalDeleteMessage"><i class="fa fa-trash"></i></button>';
                return $btn;
        })
        ->addColumn('action_send', function($data){
          if ( ( ($data->send_at == null) or ($data->send_at > now())) and ($data->sent_at == null) ){
            $btn = '<button type="button" id="sendMessage" name="sendMessage" class="btn btn-outline-success btn-sm" data-msg-id="'.$data->id.'"
               data-msg-title="'.$data->title.'"><i class="far fa-paper-plane"></i></button>';
            return $btn;
          };
        })
        ->editColumn('title', function($msg){
          if (( isset($msg->sent_at) and ($msg->sent_at) < now() )){
            return $msg->title;
          } else {
            return '<a href="' . route('message.edit', ['language'=>app()->getLocale(), 'message' =>$msg->id]) .'">'.$msg->title.' <i class="fas fa-arrow-circle-right"></i></a>';
          }
        })
        ->editColumn('updated_at', function ($msg) use ($language) {
                return Carbon::parse($msg->updated_at)->locale( $language )->isoFormat('LLL');
            })
        ->editColumn('send_at', function ($msg) use ($language) {
            if ($msg->send_at != null ){
              return Carbon::parse($msg->send_at)->locale( $language )->isoFormat('L');
            } else {
              return null;
            };
            })
        ->editColumn('sent_at', function ($msg) use ($language) {
            if ($msg->sent_at != null ){
              return Carbon::parse($msg->sent_at)->locale( $language )->isoFormat('L');
            } else {
              return null;
            }
            })
        ->editColumn('body', function ($msg) {
              return  Str::substr($msg->body,0,20);
            })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($language)
    {
      Log::info('create new message');
      return view('message/message_new', ['scopetype' => Role::getInstances()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug(print_r($request->all(),true));
        $data = $request->validate( [
            'title' => 'required|string|max:20',
            'body' => 'required|string',
            'greeting' => 'required|string',
            'salutation' => 'required|string',
            'send_at' => 'required|date|after:today',
            'author' => 'required|exists:users,id',
            'dest_region_id' => 'required|max:5|exists:regions,code',
            'dest_to.*' => ['required', new EnumValue(Role::class, false)],
            'dest_cc.*' => [ new EnumValue(Role::class, false)],
        ]);

        Log::info(print_r($data, true));
        $region = $data['dest_region_id'];
        unset($data['dest_region_id']);

        if ( isset($data['dest_to'])) {
          $dest_tos = $data['dest_to'];
          unset($data['dest_to']);
        } else {
          $dest_tos = [];
        }


        if ( isset($data['dest_cc'])){
          $dest_ccs = $data['dest_cc'];
          unset($data['dest_cc']);
        } else {
          $dest_ccs = [];
        }

        $msg = Message::create($data);

        foreach ($dest_tos as $d){
          $dest = $msg->destinations()->create([
              'scope' => $d,
              'region' => $region,
              'type' => new MessageType( MessageType::to),
          ]);
        }
        foreach ($dest_ccs as $d){
          $dest = $msg->destinations()->create([
              'scope' => $d,
              'region' => $region,
              'type' => new MessageType( MessageType::cc),
          ]);
        }

        // msg for USers
        // $test = Message::whereHas('destinations', function( Builder $q) {
        //    $q->where('region',Auth::user()->region);
        //  })->count();
        // Log::debug(print_r($test,true));

        return redirect()->route('message.index', ['language' => app()->getLocale()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit($language, Message $message)
    {
      Log::info('editing message '.print_r($message->id,true));
      $dest_to = $message->destinations()->where('type', new MessageType( MessageType::to) )->get()->pluck('scope');
      $dest_cc = $message->destinations()->where('type', new MessageType( MessageType::cc) )->get()->pluck('scope');

      $data = array();
      $data['message'] = $message;
      $data['dest_to'] = $dest_to;
      $data['dest_cc'] = $dest_cc;
      Log::debug(print_r($data,true));

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
      Log::debug(print_r($request->all(),true));
      $data = $request->validate( [
          'title' => 'required|string|max:20',
          'body' => 'required|string',
          'greeting' => 'required|string',
          'salutation' => 'required|string',
          'send_at' => 'date|after:today',
          'author' => 'required|exists:users,id',
          'dest_region_id' => 'required|max:5|exists:regions,code',
          'dest_to.*' => ['required', new EnumValue(Role::class, false)],
          'dest_cc.*' => [ new EnumValue(Role::class, false)],
      ]);

      Log::info(print_r($data, true));

      $message->destinations()->delete();

      $region = $data['dest_region_id'];
      unset($data['dest_region_id']);

      if ( isset($data['dest_to'])) {
        $dest_tos = $data['dest_to'];
        unset($data['dest_to']);
      } else {
        $dest_tos = [];
      }


      if ( isset($data['dest_cc'])){
        $dest_ccs = $data['dest_cc'];
        unset($data['dest_cc']);
      } else {
        $dest_ccs = [];
      }

      $message->update($data);

      foreach ($dest_tos as $d){
        $dest = $message->destinations()->create([
            'scope' => $d,
            'region' => $region,
            'type' => new MessageType( MessageType::to),
        ]);
      }
      foreach ($dest_ccs as $d){
        $dest = $message->destinations()->create([
            'scope' => $d,
            'region' => $region,
            'type' => new MessageType( MessageType::cc),
        ]);
      }

      return redirect()->route('message.index', ['language' => app()->getLocale()]);
    }

    /**
     * sedn notification for this message
     *
     * @param  $language
     * @param  \App\Models\Message  $message
     */
    public function send($language, Message $message)
    {
      Log::info('will prepare notification for: '.$message->title);

      ProcessCustomMessages::dispatch($message);
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
        $message->destinations()->delete();
        $message->delete();

        return redirect()->route('message.index', app()->getLocale());
    }
}
