<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ingredients;
use App\Models\recipes;
use App\Models\categories;
use App\Models\instructions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public function index(){
        $url=url('/userform');
        $user=new User;
        $title="Registration";
        $data=compact('url','title','user');
        return view('Forms.Userform')->with($data);
    }
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:8',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role');
        $user->password = md5($request->input('password'));
        $user->save();
        return redirect('/usertable');

    }
    public function view(){
        $user=User::all();
        $categories=categories::all();
        $data=compact('user','categories');
        return view('tables.UserTable')->with($data);

    }

    public function delete($id){
        $user=User::find($id);
        if(!is_null($user)){
            $user->delete();
        }
            return redirect()->back();
    }

    public function edit($id){
        $user=User::find($id);
        if(is_null($user)){
                return redirect('userview');
        }
        else{
            $url=url('/user/update')."/".$id;
            $title="Update";
            $data=compact('user','url','title');
            return view('Forms.UserForm')->with($data);

        }
    }
    public function update($id,Request $request){
        $user=User::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|numeric|between:0,1',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->role_id = $request['role'];
        $user->save();
        return redirect('/usertable');
    }

}
