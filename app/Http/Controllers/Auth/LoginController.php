<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Permission\Models\Role;

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
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('super admin')) {
            return redirect('/category');
        }

        return redirect()->route('cashier.index');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware(['role:super-admin']);
    }

    public function phone_number()
    {
        return 'phone_number';
    }

    // public function create()
    // {
    //     Role::create([
    //         'name' => 'super admin',
    //         'guard_name' => 'web'
    //     ]);

    //     Role::create([
    //         'name' => 'admin',
    //         'guard_name' => 'web'
    //     ]);

    //     Role::create([
    //         'name' => 'cashier',
    //         'guard_name' => 'web'
    //     ]);

    //     Role::create([
    //         'name' => 'customers',
    //         'guard_name' => 'web'
    //     ]);
    // }
}
