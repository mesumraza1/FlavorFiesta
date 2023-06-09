<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\FrontendController;
use App\Models\Categories;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class FrontendControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_view_with_data()
    {
        $categories = Categories::factory()->count(3)->create();
        $recipes = Recipe::factory()->count(5)->create();

        $searchTerm = 'test';
        $requestData = [
            'term' => $searchTerm,
        ];

        $response = $this->get('/')->call('GET', '/', $requestData);

        $response->assertViewIs('welcome');
        $response->assertViewHas('recipes');
        $response->assertViewHas('categories');
        $this->assertEquals(5, $response->original->getData()['recipes']->count());
        $this->assertEquals(3, $response->original->getData()['categories']->count());
    }

    public function test_dashboard_method_returns_view_with_data()
    {
        $categories = Categories::factory()->count(3)->create();
        $recipes = Recipe::factory()->count(30)->create();

        $response = $this->get('/dashboard');

        $response->assertViewIs('dashboard');
        $response->assertViewHas('recipes');
        $response->assertViewHas('categories');
        $this->assertEquals(30, $response->original->getData()['recipes']->count());
        $this->assertEquals(3, $response->original->getData()['categories']->count());
    }

    public function test_viewrecipe_method_returns_view_with_data()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->get('/viewrecipe/' . $recipe->id);

        $response->assertViewIs('mainviews.recipeview');
        $response->assertViewHas('recipe');
        $this->assertEquals($recipe->id, $response->original->getData()['recipe']->id);
    }

    public function test_viewcategory_method_returns_view_with_data()
    {
        $category = Categories::factory()->create();
        $recipes = Recipe::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->get('/viewcategory/' . $category->id);

        $response->assertViewIs('mainviews.categoryview');
        $response->assertViewHas('recipes');
        $response->assertViewHas('category');
        $response->assertViewHas('types');
        $this->assertEquals(3, $response->original->getData()['recipes']->count());
        $this->assertEquals($category->id, $response->original->getData()['category']->id);
        $this->assertEquals(1, $response->original->getData()['types']->count());
    }

    public function test_aboutus_method_returns_view_with_data()
    {
        $categories = Categories::factory()->count(3)->create();

        $response = $this->get('/aboutus');

        $response->assertViewIs('AboutUs');
        $response->assertViewHas('categories');
        $this->assertEquals(3, $response->original->getData()['categories']->count());
    }
}
