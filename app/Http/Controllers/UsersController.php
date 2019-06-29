<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
           'create', 'show','store'
        ]);
        $this->middleware('guest')->only('create');
    }



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
        $this->authorize("update",$user);
        return view('users.edit', compact('user'));
    }

    /**资料更新
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user,Request $request)
    {
        $this->authorize("update",$user);
        $this->validate($request,[
            'name'=>'required|max:20',
            'password'=>'nullable|confirmed|min:6'
        ]);
        $data['name']=$request->name;
        if ($request->password){
            $data['password']=bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success','资料更新成功');
        return redirect()->route('users.show',$user->id);
    }
}
