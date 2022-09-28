<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Message;
use Illuminate\Support\Carbon;
use Tests\Support\Authentication;
use Tests\TestCase;

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
            ->get(route('message.index', ['language' => 'de', 'region' => $this->region, 'user' => $this->region_user]));

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
            ->get(route('message.create', ['language' => 'de', 'region' => $this->region, 'user' => $this->region_user]));

        $response->assertStatus(200)
            ->assertViewIs('message.message_new')
            ->assertViewHas('scopetype', Role::getInstances());
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
            ->post(route('message.store', ['region' => $this->region, 'user' => $this->region_user]), [
                'title' => 'testmessage',
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['body', 'greeting']);
        //$response->dumpSession();
        $this->assertDatabaseMissing('messages', ['title' => 'testmessage']);
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
            ->post(route('message.store', ['region' => $this->region, 'user' => $this->region_user]), [
                'title' => 'testmessage',
                'body' => 'this is a test',
                'greeting' => 'hello',
                'salutation' => 'all',
                'send_at' => now(),
                'delete_at' => now()->addDays(3),
                'to_members' => [Role::getRandomValue()],
                'cc_members' => [Role::getRandomValue()],
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('message.index', ['language' => 'de', 'region' => $this->region, 'user' => $this->region_user]));

        $this->assertDatabaseHas('messages', ['title' => 'testmessage']);
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
        $message = Message::where('title', 'testmessage')->first();

        $response = $this->authenticated()
            ->get(route('message.edit', ['language' => 'de', 'message' => $message]));

        $response->assertStatus(200)
            ->assertViewIs('message.message_edit')
            ->assertViewHas('scopetype', Role::getInstances())
            ->assertViewHas('message', $message);
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
        $message = Message::where('title', 'testmessage')->first();
        $response = $this->authenticated()
            ->put(route('message.update', ['message' => $message]), [
                'title' => 'testmessage2',
                'greeting' => null,
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['greeting']);
        //$response->dumpSession();
        $this->assertDatabaseMissing('messages', ['title' => 'testmessage2']);
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
        $message = Message::where('title', 'testmessage')->first();
        $response = $this->authenticated()
            ->put(route('message.update', ['message' => $message]), [
                'title' => 'testmessage2',
                'body' => $message->body,
                'greeting' => $message->greeting,
                'salutation' => 'du',
                'send_at' => Carbon::now()->addDay(),
                'to_members' => [Role::getRandomValue()],
                'cc_members' => [Role::getRandomValue()],
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('message.index', ['language' => 'de', 'region' => $this->region, 'user' => $this->region_user]));

        $this->assertDatabaseHas('messages', ['title' => 'testmessage2']);
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
    public function datatable_user()
    {
        $msgs = $this->region_user->messages()->first();
        $response = $this->authenticated()
            ->get(route('message.user.dt', ['language' => 'de', 'region' => $this->region, 'user' => $this->region_user]));

        //  $response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment(['salutation' => $msgs->salutation]);
    }

    /**
     * copy
     *
     * @test
     * @group message
     * @group controller
     *
     * @return void
     */
    public function copy()
    {
        $this->assertDatabaseHas('messages', ['title' => 'testmessage2']);
        $message = Message::where('title', 'testmessage2')->first();
        $m_count = Message::count();

        $response = $this->authenticated()
            ->post(route('message.copy', ['message' => $message, 'language' => 'de']));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('messages', ['title' => 'testmessage2']);
        $this->assertDatabaseCount('messages', $m_count + 1);
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
        $message = Message::where('title', 'testmessage2')->first();
        $response = $this->authenticated()
            ->delete(route('message.destroy', ['message' => $message]));
        $message = Message::where('title', 'testmessage2')->first();
        $response = $this->authenticated()
            ->delete(route('message.destroy', ['message' => $message]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('message.index', ['language' => 'de', 'region' => $this->region, 'user' => $this->region_user]));

        $this->assertDatabaseMissing('messages', ['title' => 'testmessage2']);
    }
}
