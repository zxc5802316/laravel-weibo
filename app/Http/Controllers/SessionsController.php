<?php

namespace App\Http\Controllers;

use const http\Client\Curl\AUTH_ANY;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['create']);
    }

    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request){

        $credentials =$this->validate($request,[
            'email'=>'required|email|max:200',
            'password'=>'required|min:6'
        ]);
        if (!\Auth::attempt($credentials,$request->has('remember'))){
            session()->flash('danger','用户名或密码错误！');
            return redirect()->back()->withInput();
        }
        if (!\Auth::user()->activated){
            session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
            return redirect('/');
        }
        session()->flash('success','欢迎回家');
        $fallback = route('users.show',\Auth::user());
        return redirect()->intended($fallback);
    }

    public function destroy(){
        \Auth::logout();
        session()->flash('success','您已成功退出了！');
        return redirect()->route('login');
    }
}
