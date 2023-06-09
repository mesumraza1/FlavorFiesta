<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\FavoriteController;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_toggleFavorite_method_with_existing_favorite()
    {
        $user = $this->signIn();

        $recipe = Recipe::factory()->create();
        $user->favoriteRecipes()->attach($recipe->id);

        $requestData = [
            'recipeId' => $recipe->id,
        ];

        $response = $this->post('/toggle-favorite', $requestData);

        $response->assertJson(['message' => 'Recipe removed from favorites.']);
        $this->assertDatabaseMissing('favorite_recipe_user', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_toggleFavorite_method_with_non_existing_favorite()
    {
        $user = $this->signIn();

        $recipe = Recipe::factory()->create();

        $requestData = [
            'recipeId' => $recipe->id,
        ];

        $response = $this->post('/toggle-favorite', $requestData);

        $response->assertJson(['message' => 'Recipe added to favorites.']);
        $this->assertDatabaseHas('favorite_recipe_user', [
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    private function signIn()
    {
        $user = User::factory()->create();
        Auth::login($user);

        return $user;
    }
}
