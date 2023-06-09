<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\RecipesController;
use App\Models\Categories;
use App\Models\Ingredients;
use App\Models\Recipes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_data()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $response = $this->get('/recipeindex');

        $response->assertViewIs('recipe.recipeform');
        $response->assertViewHas('recipes');
        $this->assertInstanceOf(Recipes::class, $response->original->getData()['recipes']);
    }

    public function test_view_method_returns_view_with_data()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $categories = Categories::factory()->count(3)->create();
        $ingredients = Ingredients::factory()->count(5)->create();

        $response = $this->get('/recipeview');

        $response->assertViewIs('Forms.recipeform');
        $response->assertViewHas('categories');
        $response->assertViewHas('url');
        $response->assertViewHas('ingredients');
        $response->assertViewHas('recipes');
        $this->assertEquals(3, $response->original->getData()['categories']->count());
        $this->assertEquals(url('/recipeform'), $response->original->getData()['url']);
        $this->assertEquals(5, $response->original->getData()['ingredients']->count());
        $this->assertInstanceOf(Recipes::class, $response->original->getData()['recipes']);
    }

    public function test_store_method_creates_recipe_and_redirects()
    {
        Gate::shouldReceive('denies')->andReturnFalse();
        Storage::fake('cover');

        $categories = Categories::factory()->count(3)->create();
        $ingredients = Ingredients::factory()->count(5)->create();
        $coverFile = UploadedFile::fake()->image('cover.jpg');

        $postData = [
            'title' => 'Test Recipe',
            'description' => 'Test Description',
            'Instructions' => 'Test Instructions',
            'Prep_time' => 30,
            'cook_time' => 60,
            'servings' => 4,
            'cover' => $coverFile,
            'category' => $categories->pluck('id')->toArray(),
            'ingredients' => $ingredients->pluck('id')->toArray(),
        ];

        $response = $this->post('/recipestore', $postData);

        $this->assertDatabaseHas('recipes', [
            'title' => 'Test Recipe',
            'Description' => 'Test Description',
            'Instructions' => 'Test Instructions',
            'Prep_time' => 30,
            'cook_time' => 60,
            'servings' => 4,
            'cover' => 'cover/' . $coverFile->hashName(),
        ]);
        $this->assertCount(1, Recipes::all());
        $this->assertCount(5, Recipes::first()->ingredients);
        $this->assertCount(3, Recipes::first()->category);
        Storage::disk('cover')->assertExists('cover/' . $coverFile->hashName());
        $response->assertRedirect(route('recipeview'));
    }

    public function test_edit_method_returns_view_with_data()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $recipe = Recipes::factory()->create();
        $categories = Categories::factory()->count(3)->create();
        $ingredients = Ingredients::factory()->count(5)->create();

        $response = $this->get('/recipeedit/' . $recipe->id);

        $response->assertViewIs('Forms.recipeform');
        $response->assertViewHas('recipes');
        $response->assertViewHas('url');
        $response->assertViewHas('categories');
        $response->assertViewHas('ingredients');
        $this->assertInstanceOf(Recipes::class, $response->original->getData()['recipes']);
        $this->assertEquals(url('/recipe/update') . '/' . $recipe->id, $response->original->getData()['url']);
        $this->assertEquals(3, $response->original->getData()['categories']->count());
        $this->assertEquals(5, $response->original->getData()['ingredients']->count());
    }

    public function test_update_method_updates_recipe_and_redirects()
    {
        Gate::shouldReceive('denies')->andReturnFalse();
        Storage::fake('cover');

        $recipe = Recipes::factory()->create();
        $categories = Categories::factory()->count(3)->create();
        $ingredients = Ingredients::factory()->count(5)->create();
        $coverFile = UploadedFile::fake()->image('cover.jpg');

        $postData = [
            'title' => 'Updated Recipe',
            'description' => 'Updated Description',
            'Instructions' => 'Updated Instructions',
            'Prep_time' => 45,
            'cook_time' => 75,
            'servings' => 6,
            'cover' => $coverFile,
            'category' => $categories->pluck('id')->toArray(),
            'ingredients' => $ingredients->pluck('id')->toArray(),
        ];

        $response = $this->post('/recipeupdate/' . $recipe->id, $postData);

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'title' => 'Updated Recipe',
            'Description' => 'Updated Description',
            'Instructions' => 'Updated Instructions',
            'Prep_time' => 45,
            'cook_time' => 75,
            'servings' => 6,
        ]);
        $this->assertCount(5, $recipe->fresh()->ingredients);
        $this->assertCount(3, $recipe->fresh()->category);
        Storage::disk('cover')->assertExists('cover/' . $coverFile->hashName());
        $response->assertRedirect(route('recipeview'));
    }

    public function test_destroy_method_deletes_recipe_and_redirects()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $recipe = Recipes::factory()->create();
        Storage::disk('cover')->put('cover/' . $recipe->cover, '');

        $response = $this->post('/recipedestroy/' . $recipe->id);

        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
        $this->assertCount(0, Recipes::all());
        Storage::disk('cover')->assertMissing('cover/' . $recipe->cover);
        $response->assertRedirect(route('recipeview'));
    }

    public function test_table_method_returns_view_with_data()
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        $categories = Categories::factory()->count(3)->create();
        $recipes = Recipes::factory()->count(5)->create();

        $response = $this->get('/recipetable');

        $response->assertViewIs('tables.recipetable');
        $response->assertViewHas('recipes');
        $response->assertViewHas('categories');
        $this->assertCount(5, $response->original->getData()['recipes']);
        $this->assertCount(3, $response->original->getData()['categories']);
    }
}
