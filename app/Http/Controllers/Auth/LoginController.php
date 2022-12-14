<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\MarketAuthService;
use App\Services\MarketService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
    // protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * The service to authenticate actions
     *
     * @var App\Services\MarketAuthService
     */
  protected $marketAuthService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MarketAuthService $marketAuthService,MarketService $marketService)
    {
        $this->middleware('guest')->except('logout');
        $this->marketAuthService=$marketAuthService;
        parent::__construct($marketService);
    }

    /**
     * Show the application's login form.
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $authorizationUrl =$this->marketAuthService->resolveAuthorizationUrl();
        return view('auth.login')
            ->with(['authorizationUrl' => $authorizationUrl]);
    }



/**
 * validate login attempts
 * @param Request $request
 */
public function login(Request $request)
{
    $this->validateLogin($request);

    // If the class is using the ThrottlesLogins trait, we can automatically throttle
    // the login attempts for this application. We'll key this by the username and
    // the IP address of the client making these requests into this application.
    if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);

        return $this->sendLockoutResponse($request);
    }

    try {
        $tokenData = $this->marketAuthService->getPasswordToken($request->email, $request->password);

        $userData = $this->marketService->getUserInformation();

        $user = $this->registerOrUpdateUser($userData, $tokenData);

        $this->loginUser($user, $request->has('remember'));

        return redirect()->intended('home');
    } catch (ClientException $e) {
        $message = $e->getResponse()->getBody();

        if (Str::contains($message, 'invalid_credentials')) {
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }

        throw $e;

    }


}

    /**
     * resolve the user auth response
     * @return \Illuminate\Http\Response
     */

    public function authorization(Request $request)
    {
        if ($request->has('code')) {
            $tokenData = $this->marketAuthService->getCodeToken($request->code);

            $userData = $this->marketService->getUserInformation();

            $user = $this->registerOrUpdateUser($userData, $tokenData);

            $this->loginUser($user);

            // return redirect()->intended('home');
            return;
        }

        return redirect()
            ->route('login')
            ->withErrors(['You canecelled the authorization process']);
    }


    public function registerOrUpdateUser($userData,$tokenData)
    {
        return User::updateOrCreate(
            [
                'service_id' => $userData->identifier,
            ],
            [
                'grant_type' => $tokenData->grant_type,
                'access_token' => $tokenData->access_token,
                'refresh_token' => $tokenData->refresh_token,
                'token_expires_at' => $tokenData->token_expires_at,
            ]
        );
    }

    /**
     * Create a user session in the HTTP CLient
     * @return void
     */
    public function loginUser($user,$remember=true)
    {
        Auth::login($user, $remember);

        session()->regenerate();
    }
}
