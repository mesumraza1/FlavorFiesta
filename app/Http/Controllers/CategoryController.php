<?php

namespace App\Http\Controllers;
use App\Models\categories;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    
    public function index(){
        if(Gate::denies('admin')){
            abort(403);
        }

        $url=url('/categoryform');
        $category=new categories;
        $title="Registration";
        $data=compact('url','title','category');
        return view('Forms.categoryform')->with($data);
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'category' => 'required|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $category = new categories;
        $category->name = $request->input('category');
        $category->save();
        
        return redirect('/categorytable');
        

    }
    public function view(){
        if(Gate::denies('admin')){
            abort(403);
        }
        $categories=categories::all();
        $data=compact('categories');
        return view('tables.categorytable')->with($data);

    }

    public function delete($id){
        $category=categories::find($id);
        if(!is_null($category)){
            $category->delete();
        }
            return redirect()->back();
    }

    public function edit($id){

        if(Gate::denies('admin')){
            abort(403);
        }
        $category=categories::find($id);
        if(is_null($category)){
                return redirect('categoryview');
        }
        else{
            $url=url('/category/update')."/".$id;
            $title="Update";
            $data=compact('category','url','title');
            return view('Forms.categoryForm')->with($data);

        }
    }
    public function update($id,Request $request){
        $category = categories::find($id);

        $validator = Validator::make($request->all(), [
            'category' => 'required|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $category->name = $request->input('category');
        $category->save();
        
        return redirect('/categorytable');
        
    }
}
