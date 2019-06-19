<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\CustomException;
use App\Services\CaptchaService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        AuthenticatesUsers::login as doLogin;
    }

    /**
     * Where to redirect users after login.
     * 如果存在redirectTo方法，那么redirectTo方法的返回值会覆盖此值
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web-admin')->except(['logout', 'isLogin']);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'account';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return $this->res('登陆成功');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return $this->res('登出成功');
    }

    //将trait的login命名为doLogin，在login之前做captcha的验证
    public function login(Request $request)
    {
//        $this->validateCaptcha($request);
        return $this->doLogin($request);
    }

    protected function validateCaptcha(Request $request)
    {
        return CaptchaService::validateCaptcha($request);
    }

    public function guard()
    {
        return Auth::guard('web-admin');
    }

    public function isLogin(Request $request)
    {
        $user = $this->guard()->user();
        $returnData = $user ? true : false;
        return $this->res('success', $returnData);
    }
}
