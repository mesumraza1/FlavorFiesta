<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_data()
    {
        $response = $this->get('/userindex');

        $response->assertViewIs('Forms.Userform');
        $response->assertViewHas('url');
        $response->assertViewHas('title');
        $response->assertViewHas('user');
        $this->assertEquals(url('/userform'), $response->original->getData()['url']);
        $this->assertEquals('Registration', $response->original->getData()['title']);
        $this->assertInstanceOf(User::class, $response->original->getData()['user']);
    }

    public function test_register_method_creates_user_and_redirects()
    {
        $postData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'role' => 'admin',
            'password' => 'password',
        ];

        $response = $this->post('/userregister', $postData);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'role_id' => 'admin',
        ]);
        $response->assertRedirect('/usertable');
    }

    public function test_view_method_returns_view_with_data()
    {
        $users = User::factory()->count(5)->create();
        $categories = categories::all();

        $response = $this->get('/userview');

        $response->assertViewIs('tables.UserTable');
        $response->assertViewHas('user');
        $response->assertViewHas('categories');
        $this->assertCount(5, $response->original->getData()['user']);
        $this->assertCount(0, $response->original->getData()['categories']);
    }

    public function test_delete_method_deletes_user_and_redirects()
    {
        $user = User::factory()->create();

        $response = $this->post('/userdelete/' . $user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertCount(0, User::all());
        $response->assertRedirect('/userview');
    }

    public function test_edit_method_returns_view_with_data()
    {
        $user = User::factory()->create();

        $response = $this->get('/useredit/' . $user->id);

        $response->assertViewIs('Forms.UserForm');
        $response->assertViewHas('user');
        $response->assertViewHas('url');
        $response->assertViewHas('title');
        $this->assertInstanceOf(User::class, $response->original->getData()['user']);
        $this->assertEquals(url('/user/update') . '/' . $user->id, $response->original->getData()['url']);
        $this->assertEquals('Update', $response->original->getData()['title']);
    }

    public function test_update_method_updates_user_and_redirects()
    {
        $user = User::factory()->create();

        $postData = [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
        ];

        $response = $this->post('/userupdate/' . $user->id, $postData);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
        ]);
        $this->assertEquals('Updated User', $user->fresh()->name);
        $this->assertEquals('updateduser@example.com', $user->fresh()->email);
        $response->assertRedirect('/usertable');
    }
}
