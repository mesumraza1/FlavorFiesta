<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\CategoryController;
use App\Models\categories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_authorization()
    {
        Gate::shouldReceive('denies')
            ->once()
            ->with('admin')
            ->andReturn(false);

        $response = $this->get('/category');

        $response->assertStatus(403);
    }

    public function test_index_method_returns_view()
    {
        Gate::shouldReceive('denies')
            ->once()
            ->with('admin')
            ->andReturn(true);

        $response = $this->get('/category');

        $response->assertStatus(200);
        $response->assertViewIs('Forms.categoryform');
        $response->assertViewHas('url');
        $response->assertViewHas('title');
        $response->assertViewHas('category');
    }

    public function test_register_method()
    {
        $requestData = [
            'category' => 'Test Category',
        ];

        $response = $this->post('/category/register', $requestData);

        $response->assertRedirect('/categorytable');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    }

    public function test_view_method_authorization()
    {
        Gate::shouldReceive('denies')
            ->once()
            ->with('admin')
            ->andReturn(false);

        $response = $this->get('/categorytable');

        $response->assertStatus(403);
    }

    public function test_view_method_returns_view()
    {
        Gate::shouldReceive('denies')
            ->once()
            ->with('admin')
            ->andReturn(true);

        $categories = categories::factory()->create();

        $response = $this->get('/categorytable');

        $response->assertStatus(200);
        $response->assertViewIs('tables.categorytable');
        $response->assertViewHas('categories');
    }

    public function test_delete_method()
    {
        $category = categories::factory()->create();

        $response = $this->get('/category/delete/' . $category->id);

        $response->assertRedirect();

        $this->assertDeleted($category);
    }

    public function test_edit_method_authorization()
    {
        Gate::shouldReceive('denies')
            ->once()
            ->with('admin')
            ->andReturn(false);

        $category = categories::factory()->create();

        $response = $this->get('/category/edit/' . $category->id);

        $response->assertStatus(403);
    }

    public function test_edit_method_with_valid_category()
    {
        Gate::shouldReceive('denies')
            ->once()
            ->with('admin')
            ->andReturn(true);

        $category = categories::factory()->create();

        $response = $this->get('/category/edit/' . $category->id);

        $response->assertStatus(200);
        $response->assertViewIs('Forms.categoryForm');
        $response->assertViewHas('category');
        $response->assertViewHas('url');
        $response->assertViewHas('title');
    }

    public function test_edit_method_with_invalid_category()
    {
        Gate::shouldReceive('denies')
            ->once()
            ->with('admin')
            ->andReturn(true);

        $response = $this->get('/category/edit/999');

        $response->assertRedirect('/categoryview');
    }

    public function test_update_method()
    {
        $category = categories::factory()->create();

        $requestData = [
            'category' => 'Updated Category',
        ];

        $response = $this->post('/category/update/' . $category->id, $requestData);

        $response->assertRedirect('/categorytable');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }
}
