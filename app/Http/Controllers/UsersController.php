<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
           'create', 'show','store','index','confirmEmail'
        ]);
        $this->middleware('guest')->only('create');
    }

    public function index(){


       $users =  User::paginate(10);

       return view('users.index',compact('users'));
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
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate(10);

        return view('users.show', compact('user','statuses'));
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
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');

        return redirect("/");
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

    public function destroy(User $user){
        $user->delete();
        session()->flash('success','删除成功');
        return back();
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        \Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        \Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
