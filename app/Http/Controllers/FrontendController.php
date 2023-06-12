<?php

namespace App\Http\Controllers;
use App\Models\ingredients;
use App\Models\recipes;
use App\Models\categories;
use App\Models\instructions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    //
    public function index(Request $request){
        $categories=categories::all();

        $searchTerm = $request->input('term');
    $query = recipes::query()->with('category');

    if (!empty($searchTerm)) {
        $query->where('title', 'like', '%'.$searchTerm.'%');
    }

    $recipes = $query->paginate(30);

        return view('welcome',compact('recipes','categories'));
    }
    public function dashboard(Request $request)
{
    $categories = categories::all();
    $query = recipes::query()->with('category');

    $searchTerm = $request->input('term');
    if (!empty($searchTerm)) {
        $query->where('title', 'like', '%' . $searchTerm . '%');
    }

    $recipes = $query->paginate(30);

    return view('dashboard', compact('recipes', 'categories'));
}


    public function viewrecipe($id){

        $recipe = recipes::with('ingredients', 'category')->findorfail($id);

        return view('mainviews.recipeview',compact('recipe'));
    }

    public function viewcategory(Request $request, $id)
{
    $types = categories::all();
    $category = categories::findOrFail($id);
    $query = $category->recipe();

    $searchTerm = $request->input('term');
    if (!empty($searchTerm)) {
        $query->where('title', 'like', '%' . $searchTerm . '%');
    }

    $recipes = $query->paginate(30);

    return view('mainviews.categoryview', compact('recipes', 'category', 'types'));
}


    
    public function aboutus(){
        $categories=categories::all();
        return view('AboutUs',compact('categories'));
    }

}
