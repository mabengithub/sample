<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{

    //未登录访问权限
    public function __construct()    {
	
	$this->middleware('auth', [

	    'except' => ['index','show', 'create', 'store', 'confirmEmail']

        ]);

	//只允许未登录的用户访问
	$this->middleware('guest', [
		
	    'only' => ['create']
	]);


    }

    //用户列表
    public function index()
    {
	//$users = User::all();
	$users = User::paginate(10);

	return view('users.index', compact('users'));
    }

    //用户注册
    public function create()
    {
       
	return view('users.create'); 
    
    }

    //修改页面
    public function edit(User $user)
    {
        //登录用户修改别人的资料异常
	try{
	
	    $this->authorize('update', $user);

	    return view('users.edit', compact('user'));

	} catch(\Exception $exception) {
	    
	    //echo '无权修改其他用户资料';
	    //return redirect()->back();//重定向
	    return abort(403, '对不起，你无权访问此页面');

	}
    }

    //执行修改
    public function update(User $user, Request $request)
    {
    	$this->validate($request, [

	    'name' => 'required|max:50',

	    'password' => 'nullable|confirmed|min:6'
        ]);

	$this->authorize('update', $user);

	$data = [];

	$data['name'] = $request->name;

	if ($request->password) {

	    $data['password'] = bcrypt($request->password);
	}


	$user->update($data);

	session()->flash('success', '个人资料更新成功!');

	return redirect()->route('users.show', $user->id);


    }
     
    //删除
    public function destroy(User $user)
    {

	$this->authorize('destroy', $user);

	$user->delete();

	session()->flash('success', '成功删除用户!');

	return back();
    }

    //用户详情
    public function show(User $user)
    {

	//取出一个用户的所有微博
	$statuses = $user->statuses()
			 ->orderBy('created_at', 'desc')
			 ->paginate(30);

	return view('users.show',compact('user', 'statuses'));
    
    }

    //注册
    public function store(Request $request)
    {
	$this->validate($request, [

	    'name' => 'required|max:50',
    	    
	    'email' => 'required|email|unique:users|max:255',

	    'password' => 'required|confirmed|min:6'	    
	]);

	$user = User::create([
		
	    'name' => $request->name,

	    'email' => $request->email,

	    'password' => bcrypt($request->password),
        ]);

	$this->sendEmailConfirmationTo($user);

	session()->flash('success','验证邮箱已发送到你的注册邮箱上，请注意查收');

	return redirect('/');

	//Auth::login($user);

	//session()->flash('success','欢迎,您将在这里开启一段新的旅程~');

	//return redirect()->route('users.show', [$user]);
    }

    //发送邮箱
    protected function sendEmailConfirmationTo($user)
    {
	 $view = 'emails.confirm';

	 $data = compact('user');

	 $from = '863252131@qq.com';

	 $name = 'Sample';

	 $to = $user->email;

	 $subject = "感谢注册 Sample 应用！请确认你的邮箱";

	 Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {

	     #$message->from($from, $name)->to($to)->subject($subject);
	     //已经在环境配置完善了邮箱的发送配置，因此不在需要使用form方法
	     $message->to($to)->subject($subject);


	 });
    }

    //确认邮箱
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

	$user->activated = true;

	$user->activation_token = null;

	$user->save();


	Auth::login($user);

	session()->flash('success', '恭喜你，激活成功!');

	return redirect()->route('users.show', [$user]);

    }

    //
    public function feed()
    {
    	return $this->statuses()
		    ->orderBy('created_at', 'desc');
    }
}



