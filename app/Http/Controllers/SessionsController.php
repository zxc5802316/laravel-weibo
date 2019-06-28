<?php

namespace App\Http\Controllers;

use const http\Client\Curl\AUTH_ANY;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request){

        $credentials =$this->validate($request,[
            'email'=>'required|email|max:200',
            'password'=>'required|min:6'
        ]);
        if (!\Auth::attempt($credentials)){
            session()->flash('danger','用户名或密码错误！');
            return redirect()->back()->withInput();
        }
        session()->flash('success','欢迎回家');
        return redirect()->route('users.show',\Auth::user());
    }
}
