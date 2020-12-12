<?php

namespace Tests\Unit;

use App\Models\Message;
use App\Models\Region;
use App\Enums\Role;
use App\Http\Controllers\MessageController;

use Carbon\Carbon;
use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class MessageControllerTest extends TestCase
{
    use Authentication;

    /**
     * index
     *
     * @test
     * @group message
     * @group controller
     * @covers App\Http\Controllers\MessageController::index
     *
     * @return void
     */
    public function index()
    {

      $response = $this->authenticated()
                        ->get(route('message.index',['language'=>'de']));

      $response->assertStatus(200)
               ->assertViewIs('message.message_list');

    }
    /**
     * create
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function create()
    {

      $response = $this->authenticated()
                        ->get(route('message.create',['language'=>'de']));

      $response->assertStatus(200)
               ->assertViewIs('message.message_new')
               ->assertViewHas('scopetype',Role::getInstances());

    }
    /**
     * store not OK
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
      //$this->withoutExceptionHandling();
      $response = $this->authenticated()
                        ->post(route('message.store',['region'=>$this->region]),[
                          'title' => 'testmessage',
                          'author' => 99,
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['author']);
      //$response->dumpSession();
      $this->assertDatabaseMissing('messages', ['title'=>'testmessage']);
    }

    /**
     * store
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {
      //$this->withoutExceptionHandling();
      $response = $this->authenticated()
                        ->post(route('message.store',['region'=>$this->region]),[
                          'title' => 'testmessage',
                          'body' => 'this is a test',
                          'greeting' => 'hello',
                          'salutation' => 'all',
                          'send_at' => now(),
                          'author' => $this->region_user->id,
                          'dest_to' => [Role::getRandomValue()],
                          'dest_cc' => [Role::getRandomValue()],
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('message.index',['language'=>'de']));

      $this->assertDatabaseHas('messages', ['title'=>'testmessage']);
    }
    /**
     * edit
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
      //$this->withoutExceptionHandling();
      $message = Message::where('title','testmessage')->first();

      $response = $this->authenticated()
                        ->get(route('message.edit',['language'=>'de', 'message'=>$message]));

      $response->assertStatus(200)
               ->assertViewIs('message.message_edit')
               ->assertViewHas('scopetype',Role::getInstances())
               ->assertViewHas('message.message',$message);

    }
    /**
     * update not OK
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
      //$this->withoutExceptionHandling();
      $message = Message::where('title','testmessage')->first();
      $response = $this->authenticated()
                        ->put(route('message.update',['region'=>$this->region, 'message'=>$message]),[
                          'title' => 'testmessage2',
                          'author' => 99,
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['author']);;
      //$response->dumpSession();
      $this->assertDatabaseMissing('messages', ['title'=>'testmessage2']);
    }
    /**
     * update OK
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
      //$this->withoutExceptionHandling();
      $message = Message::where('title','testmessage')->first();
      $response = $this->authenticated()
                        ->put(route('message.update',['region'=>$this->region, 'message'=>$message]),[
                          'title' => 'testmessage2',
                          'body' => $message->body,
                          'greeting' => $message->greeting,
                          'salutation' => 'du',
                          'send_at' => Carbon::now()->addDay(),
                          'author' => $this->region_user->id,
                          'dest_to' => [Role::getRandomValue()],
                          'dest_cc' => [Role::getRandomValue()],
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('message.index',['language'=>'de']));

      $this->assertDatabaseHas('messages', ['title'=>'testmessage2']);
    }
    /**
     * list_user_dt
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function list_user_dt()
    {
      $msgs = $this->region_user->messages()->first();
      $response = $this->authenticated()
                        ->get(route('message.user.dt',['language'=>'de','user'=>$this->region_user]));

//      $response->dump();
      $response->assertStatus(200)
               ->assertJsonPath('data.*.salutation', [$msgs->salutation]);
    }

    /**
     * destroy
     *
     * @test
     * @group message
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
      //$this->withoutExceptionHandling();
      $message = Message::where('title','testmessage2')->first();
      $response = $this->authenticated()
                        ->delete(route('message.destroy',['message'=>$message]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('message.index',['language'=>'de']));

      $this->assertDatabaseMissing('messages', ['title'=>'testmessage2']);
    }

}
