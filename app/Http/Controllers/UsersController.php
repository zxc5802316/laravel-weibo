<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**用户注册
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){


        return view('users.create');
    }

    /**个人信息
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user){
        //图片输出
        return view('users.show',compact('user'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required|min:3|max:20',
            'email'=>'required|email|unique:users,email|max:200',
            'password'=>'required|min:6|confirmed'
        ]);

       $user =  User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
//       注册后 自动登录
       \Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
       return redirect()->route('users.show',[$user]);
    }
}
