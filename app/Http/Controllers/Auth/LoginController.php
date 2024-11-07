<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

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
        $user = AdminUser::where($this->username(), $request->{$this->username()})->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            toastr()->warning('ログイン名またはパスワードが間違っています。')->setTitle('ログイン失敗');
            throw ValidationException::withMessages([
                $this->username() => [trans('')],
            ]);
        }
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
