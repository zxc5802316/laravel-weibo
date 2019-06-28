<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**用户注册模板
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {


        return view('users.create');
    }

    /**个人信息
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        //图片输出
        return view('users.show', compact('user'));
    }

    /**用户注册
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'     => 'required|min:3|max:20',
                'email'    => 'required|email|unique:users,email|max:200',
                'password' => 'required|min:6|confirmed',
            ]
        );

        $user = User::create(
            [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]
        );
//       注册后 自动登录
        \Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        return redirect()->route('users.show', [$user]);
    }

    /**更新资料页面
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    public function update(User $user,Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:20',
            'password'=>'required|confirmed|min:6'
        ]);
        $user->update([
            'name'=>$request->name,
            'password'=>bcrypt($request->password),
        ]);
        session()->flash('success','资料更新成功');
        return redirect()->route('users.show',$user->id);
    }
}
