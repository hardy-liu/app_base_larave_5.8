<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Exceptions\CustomException;
use App\Services\CaptchaService;
use Carbon\Carbon;
use App\Services\UserSessionIdCacheService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        //将trait的login命名为doLogin，在login之前做captcha的验证
        AuthenticatesUsers::login as doLogin;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest:web')->except(['logout', 'isLogin']);
        parent::__construct($request);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * 用户登录
     *
     * @OA\Post(
     *     path="/api/login",
     *     description="用户登录",
     *     operationId="api.login.post",
     *     tags={"user"},
     *
     *     @OA\RequestBody(
     *         description="upload request body",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email", "password", "captcha"},
     *                 @OA\Property(
     *                     property="email",
     *                     description="用户邮箱",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="用户密码",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="captcha",
     *                     description="验证码",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="remember",
     *                     description="记住账号(值赋1，或者空，或者不传此字段)",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/Success"),
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="验证器错误",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError"),
     *     ),
     * )
     */
    public function login(Request $request)
    {
        $this->validateCaptcha($request);
        $this->validateOtherStatus($request);
        $this->doLogin($request);
        return $this->res('登陆成功');
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
        $user->last_login_ip = $request->getClientIp();
        $user->last_login_time = Carbon::now()->toDateTimeString();
        $user->save();

        //保存此登录用的sessionId到redis中
        $userSessionIdCacheService = new UserSessionIdCacheService();
        $userSessionIdCacheService->storeSessionId($request, $user);

        return redirect()->intended($this->redirectPath());
    }

    protected function validateOtherStatus($request)
    {
        $user = User::where($this->username(), $request->input($this->username()))->first();
        if (! $user) {
            return true;    //如果用户不存在直接跳过，进入login逻辑（报错帐号密码不匹配）
        }
        $this->verifyActive($request, $user);
        $this->verifyEmailStatus($request, $user);
        $this->verifyPhoneStatus($request, $user);
    }

    protected function verifyActive($request, $user)
    {
        if ($user->active !== 1) {
            throw new CustomException('account is not available.');
        }
        return true;
    }

    protected function verifyEmailStatus($request, $user)
    {
        if ($user->email_verification_status !== 1) {
            throw new CustomException('account email is not verified.');
        }
        return true;
    }

    protected function verifyPhoneStatus($request, $user)
    {
        if ($user->phone_verification_status !== 1) {
            throw new CustomException('account phone is not verified.');
        }
        return true;
    }

    protected function validateCaptcha(Request $request)
    {
        return CaptchaService::validateCaptcha($request);
    }

    /**
     * 用户登出
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/api/logout",
     *     description="用户登出",
     *     operationId="api.logout.post",
     *     tags={"user"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/Success"),
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $sessionId = $request->session()->getId();
        $userId = $this->guard()->id();

        $this->guard()->logout();

        $request->session()->invalidate();

        //删除redis中缓存的用户session id
        $userSessionIdCacheService = new UserSessionIdCacheService();
        $userSessionIdCacheService->deleteSessionId($sessionId, $userId);

        //return redirect('/');
        return response($this->res('登出成功'));
    }

    public function guard()
    {
        return Auth::guard('web');
    }

    /**
     * 查看用户是否登陆
     *
     * @OA\Get(
     *     path="/api/is-login",
     *     description="查看用户是否登陆",
     *     operationId="api.is-login.get",
     *     tags={"user"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/Success"),
     *     )
     * )
     */
    public function isLogin(Request $request)
    {
        $user = $this->guard()->user();
        $returnData = $user ? true : false;
        return $this->res('success', $returnData);
    }
}
