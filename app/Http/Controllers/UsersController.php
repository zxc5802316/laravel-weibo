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
        return view('users.show',compact('user'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required|min:3|max:20',
            'email'=>'required|email|unique:users,email|max:200',
            'password'=>'required|min:6|confirmed'
        ]);
    }
}
