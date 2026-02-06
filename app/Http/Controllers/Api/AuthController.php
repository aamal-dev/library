<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);

        $remember =  $request->boolean('remember', false);
        Auth::login($user, 1);

        session()->regenerate();

        return ResponseHelper::success("تم تسجيل جسابك بنجاح ");
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email',],
            'password' => ['required'],
        ]);
        

        $remember = $request->boolean('remember');
        if (! Auth::attempt($validated, $remember)) 
            throw ValidationException::withMessages([
                'email' => 'bad'
            ]);
            
            $request->session()->regenerate();
            return ResponseHelper::success("تم تسجيل الدخول بنجاح ");
    }

    function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
