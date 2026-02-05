<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Constants\ExtensionConst;
use App\Traits\User\LoggedInUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\Admin\ExtensionProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    protected $request_data;
    protected $lockoutTime = 1;

    use AuthenticatesUsers, LoggedInUsers;

    public function showLoginForm() {
        $page_title = setPageTitle("User Login");
        return view('user.auth.login',compact(
            'page_title',
        ));
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $extension = ExtensionProvider::get()->where('slug', ExtensionConst::GOOGLE_RECAPTCHA_SLUG)->first();
        $captcha_rules = 'nullable';
        if ($extension && $extension->status == true) {
            $captcha_rules = 'required|string|g_recaptcha_verify';
        }
        $this->request_data = $request;
        $request->validate([
            'credentials' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => $captcha_rules,
        ]);

        if (User::where($this->username(), $request->credentials)->where('status', GlobalConst::BANNED)->exists()) {
            throw ValidationException::withMessages([
                'credentials' => ['Your account has been suspended!'],
            ]);
        }
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $col = $this->username();
        $request->merge(['status' => true]);
        $request->merge([$col => $request->credentials]);
        return $request->only($col, 'password','status');
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $request = $this->request_data->all();
        $credentials = $request['credentials'];
        return filter_var($credentials, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            "credentials" => [trans('auth.failed')],
        ]);
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard("web");
    }


    /**
     * Send the response after the user was authenticated.
     * Usa 303 See Other para que el navegador siga el redirect con GET y envíe las cookies.
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        $response = $this->authenticated($request, $this->guard()->user())
            ?: redirect()->route('user.dashboard');

        $request->session()->save();

        return $response->setStatusCode(303);
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
        $user->update(['two_factor_verified' => false]);

        $this->refreshUserWallets($user);
        $this->createLoginLog($user);

        return redirect()->route('user.dashboard')->setStatusCode(303);
    }

    /**
     * Ruta a la que redirigir tras el login (dashboard usuario público).
     */
    protected function redirectPath(): string
    {
        return route('user.dashboard');
    }
}
