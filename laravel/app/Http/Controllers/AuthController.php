<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Mail\reset_password;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated =  $request->validate([
            'login'=> 'required|string|unique:users,login',
            'real_name'=> 'string',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|confirmed|min:4',
            'role' => 'in:admin,user'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $role = $request->role;
        if(strcmp($role, 'admin') == 0) {
            $user = auth()->user();
            if(!isAdmin(auth()->user())) {
                return "only admin can create admin";
            }
        } 

        $user = User::create($validated);

        return response([
            'message' => 'User registered. Please log in',
            'user' => $user
        ]);
    }

    public function Login(Request $request)
    {

        $credentials = $request->only(['login', 'password']);
        if(($token = JWTAuth::attempt($credentials))) {
            $user = JWTAuth::user();
            $user->token = $token;
            $user->save();
            return response([
                'message' => 'Logged in',
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => $user
            ]);
        }
        
    }

    public function requestForPasswordReset(Request $request) {
        $validated =  $request->validate([
            'email'=> 'required|email'
        ]);

        $user = User::find(DB::table('users')->where('email', '=', $validated['email'])->first()->id);

        if (!$user) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        $token = Str::random(60);
        $user->password_reset_token = $token;
        $user->save();

        $mailObj = new \stdClass();
        $mailObj->token =  $token;
        $mailObj->receiver = $user->real_name;
        Mail::to($validated['email'])->send(new reset_password($mailObj));

        return "check your mail";

    }

    public function PasswordReset(Request $request) {
        $validated =  $request->validate([
            'email'=> 'required|email',
            'token'=> 'required|min:20',
            'password'=> 'required|confirmed|min:4',
        ]);

        $user = User::find(DB::table('users')->where('email', '=', $validated['email'])->first()->id);

        if (!$user) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        if(strcmp($validated['token'], $user->password_reset_token) == 0) {
            $user->password = Hash::make($validated['password']);
            $user->password_reset_token = "";
            $user->save();
            return "your password has been changed";
        }
        return "your token not match";

    }

    public function Logout()
    {
        try {
            $user = auth()->user();
            if($user) {
                JWTAuth::invalidate(JWTAuth::getToken());
                $user->token = '';
                $user->save();
            }
            return $user;
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response(['error' => $e->getMessage()], 401);
        }
    }
}
