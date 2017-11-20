<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
 
     public function dologin(Request $request) {

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'role_id'=> 1 ])) {
            
            return redirect('/employee');
        } else {
              return redirect('login')
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => 'Invalid credentials.',
                    ]);
        }
    }

    public function username() {
        return 'username';
    }


     

     
}
