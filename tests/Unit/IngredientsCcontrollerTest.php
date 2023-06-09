<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\IngredientsController;
use App\Models\Categories;
use App\Models\Ingredients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class IngredientsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_data()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $categories = Categories::factory()->count(3)->create();

        $response = $this->get('/ingredientindex');

        $response->assertViewIs('Forms.ingredientform');
        $response->assertViewHas('url');
        $response->assertViewHas('title');
        $response->assertViewHas('ingredient');
        $this->assertEquals(url('/ingredientform'), $response->original->getData()['url']);
        $this->assertEquals('Registration', $response->original->getData()['title']);
        $this->assertInstanceOf(Ingredients::class, $response->original->getData()['ingredient']);
    }

    public function test_register_method_saves_ingredient_and_redirects()
    {
        $ingredientData = [
            'ingredient' => 'Test Ingredient',
        ];

        $response = $this->post('/ingredientregister', $ingredientData);

        $this->assertDatabaseHas('ingredients', $ingredientData);
        $response->assertRedirect('/ingredienttable');
    }

    public function test_view_method_returns_view_with_data()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $categories = Categories::factory()->count(3)->create();
        $ingredients = Ingredients::factory()->count(5)->create();

        $response = $this->get('/ingredientview');

        $response->assertViewIs('tables.IngredientsTable');
        $response->assertViewHas('ingredient');
        $response->assertViewHas('categories');
        $this->assertEquals(5, $response->original->getData()['ingredient']->count());
        $this->assertEquals(3, $response->original->getData()['categories']->count());
    }

    public function test_delete_method_deletes_ingredient_and_redirects()
    {
        $ingredient = Ingredients::factory()->create();

        $response = $this->get('/ingredientdelete/' . $ingredient->id);

        $this->assertDeleted($ingredient);
        $response->assertRedirect();
    }

    public function test_edit_method_returns_view_with_data()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $categories = Categories::factory()->count(3)->create();
        $ingredient = Ingredients::factory()->create();

        $response = $this->get('/ingredientedit/' . $ingredient->id);

        $response->assertViewIs('Forms.ingredientForm');
        $response->assertViewHas('ingredient');
        $response->assertViewHas('url');
        $response->assertViewHas('title');
        $response->assertViewHas('categories');
        $this->assertEquals($ingredient->id, $response->original->getData()['ingredient']->id);
        $this->assertEquals(url('/ingredient/update') . '/' . $ingredient->id, $response->original->getData()['url']);
        $this->assertEquals('Update', $response->original->getData()['title']);
        $this->assertEquals(3, $response->original->getData()['categories']->count());
    }

    public function test_update_method_updates_ingredient_and_redirects()
    {
        $ingredient = Ingredients::factory()->create();

        $updatedIngredientData = [
            'ingredient' => 'Updated Ingredient',
        ];

        $response = $this->post('/ingredient/update/' . $ingredient->id, $updatedIngredientData);

        $this->assertDatabaseHas('ingredients', $updatedIngredientData);
        $response->assertRedirect('/ingredienttable');
    }
}
