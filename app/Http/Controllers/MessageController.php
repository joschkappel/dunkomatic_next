<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Enums\MessageScopeType;
use App\Enums\MessageType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Database\Eloquent\Builder;
use Datatables;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

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
      $msgs = Message::where('author', $user->id)->orderBy('valid_from','ASC')->get();

      $msglist = datatables::of($msgs);

      return $msglist
        ->rawColumns(['valid_from','valid_to', 'body','action','title'])
        ->addIndexColumn()
        ->addColumn('action', function($data){
               $btn = '<button type="button" id="deleteMessage" name="deleteMessage" class="btn btn-outline-danger btn-sm" data-msg-id="'.$data->id.'"
                  data-msg-title="'.$data->title.'" data-toggle="modal" data-target="#modalDeleteMessage"><i class="fa fa-trash"></i></button>';
                return $btn;
        })
        ->editColumn('title', function($msg){
          if ( $msg->valid_from <= now() ){
            return $msg->title;
          } else {
            return '<a href="' . route('message.edit', ['language'=>app()->getLocale(), 'message' =>$msg->id]) .'">'.$msg->title.' <i class="fas fa-arrow-circle-right"></i></a>';
          }
        })
        ->editColumn('created_at', function ($msg) use ($language) {
                return Carbon::parse($msg->created_at)->locale( $language )->isoFormat('LLL');
            })
        ->editColumn('valid_from', function ($msg) use ($language) {
              return Carbon::parse($msg->valid_from)->locale( $language )->isoFormat('L');
            })
        ->editColumn('valid_to', function ($msg) use ($language) {
              return Carbon::parse($msg->valid_to)->locale( $language )->isoFormat('L');
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
      return view('message/message_new', ['scopetype' => MessageScopeType::getInstances()]);
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
            'body' => 'required|string|max:255',
            'valid_from' => 'required|date|before:valid_to',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'author' => 'required|exists:users,id',
            'dest_region_id' => 'required|max:5|exists:regions,code',
            'dest_to.*' => ['required', new EnumValue(MessageScopeType::class, false)],
            'dest_cc.*' => [ new EnumValue(MessageScopeType::class, false)],
        ]);

        Log::info(print_r($data, true));
        $dest_tos = $data['dest_to'];
        $dest_ccs = $data['dest_cc'];
        $region = $data['dest_region_id'];
        unset($data['dest_to']);
        unset($data['dest_cc']);
        unset($data['dest_region_id']);

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

      return view('message/message_edit', ['message' => $data, 'scopetype' => MessageScopeType::getInstances()]);
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
          'body' => 'required|string|max:255',
          'valid_from' => 'required|date|before:valid_to',
          'valid_to' => 'required|date|after_or_equal:valid_from',
          'author' => 'required|exists:users,id',
          'dest_region_id' => 'required|max:5|exists:regions,code',
          'dest_to.*' => ['required', new EnumValue(MessageScopeType::class, false)],
          'dest_cc.*' => [ new EnumValue(MessageScopeType::class, false)],
      ]);

      Log::info(print_r($data, true));

      $message->destinations()->delete();

      $dest_tos = $data['dest_to'];
      $dest_ccs = $data['dest_cc'];
      $region = $data['dest_region_id'];
      unset($data['dest_to']);
      unset($data['dest_cc']);
      unset($data['dest_region_id']);

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
