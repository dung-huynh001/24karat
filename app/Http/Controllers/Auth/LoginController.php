<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\AdminUser;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';



    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //Override sendFailedLoginResponse of AuthenticatesUsers
    protected function sendFailedLoginResponse(Request $request)
    {
        toastr()->warning('ログイン名またはパスワードが間違っています。')->setTitle('ログイン失敗');
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);


        //Login fails if user is delete_flag == 1
        $user = AdminUser::where('email', $request->input('email'))->first();
        if ($user && $user->delete_flag == 1) {
            if($user->is_butterflydance_user != 1) {
                return $this->send403Response($request);
            }
            return $this->sendDisabledAccountResponse($request);
        }

        

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendDisabledAccountResponse(Request $request)
    {
        toastr()->warning('このアカウントは無効になっています。')->setTitle('ログイン失敗');
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.disabled')],
        ]);
    }

    protected function send403Response(Request $request)
    {
        toastr()->warning('このユーザーは存在しません。')->setTitle('ログイン失敗');
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.denied')],
        ]);
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
