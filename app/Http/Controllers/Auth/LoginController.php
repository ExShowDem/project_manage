<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\URL;
use Request;
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
    protected $redirectTo = '/projects';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $url_curent = URL::current();

        $contains = Str::contains($url_curent, $_SERVER['SERVER_ADDR']);

        if($contains == 1)
        {
            abort(403);
        }

        $this->middleware('guest')->except('logout');
    }
}
