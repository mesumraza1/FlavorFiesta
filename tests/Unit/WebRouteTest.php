<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\RecipesController;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_route()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    public function test_favorite_route()
    {
        $response = $this->post('/favorite');

        $response->assertStatus(500); // Assuming the route is not properly implemented, returns an error.
    }

    public function test_frontend_routes()
    {
        $routes = [
            '/',
            '/aboutus',
            '/details/1', // Replace 1 with a valid recipe ID
            '/category/1', // Replace 1 with a valid category ID
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs('frontend.index');
        }
    }

    public function test_recipe_routes()
    {
        $routes = [
            '/addrecipe',
            '/recipeform',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs('recipe.index');
        }
    }

    public function test_user_routes()
    {
        $routes = [
            '/userform',
            '/usertable',
            '/user/edit/1', // Replace 1 with a valid user ID
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs('user.index');
        }
    }

    public function test_category_routes()
    {
        $routes = [
            '/categoryform',
            '/categorytable',
            '/category/edit/1', // Replace 1 with a valid category ID
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs('category.index');
        }
    }

    public function test_ingredient_routes()
    {
        $routes = [
            '/ingredientform',
            '/ingredienttable',
            '/ingredient/edit/1', // Replace 1 with a valid ingredient ID
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs('ingredient.index');
        }
    }

    public function test_recipe_edit_route()
    {
        $response = $this->get('/recipe/edit/1'); // Replace 1 with a valid recipe ID

        $response->assertStatus(200);
        $response->assertViewIs('recipe.edit');
    }

    public function test_recipe_delete_route()
    {
        $response = $this->get('/recipe/delete/1'); // Replace 1 with a valid recipe ID

        $response->assertStatus(302); // Assuming the route is properly implemented and redirects
    }

    public function test_recipe_table_route()
    {
        $response = $this->get('/recipetable');

        $response->assertStatus(200);
        $response->assertViewIs('recipe.view');
    }
}
