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
        ]);

        $validated['password'] = Hash::make($validated['password']);

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

        $user = null;
        $temp_user = DB::table('users')->where('email', '=', $validated['email'])->first();
        if($temp_user)   
            $user = User::find($temp_user->id);

        if (!$user) {
            return response("Not Found", 404);
        }

        $token = Str::random(60);
        $user->password_reset_token = $token;
        $user->save();

        $mailObj = new \stdClass();
        $mailObj->token =  $token;
        $mailObj->receiver = $user->real_name;
        $mailObj->id = $user->id;
        $mailObj->url = 'http://localhost:3000/resetPassword/' . $token . '/' . $user->id;
        Mail::to($validated['email'])->send(new reset_password($mailObj));

        return response(['message' => "check your mail"], 200);

    }

    public function PasswordReset(Request $request, $token, $userId) {
        $validated =  $request->validate([
            'password'=> 'required|confirmed|min:4',
        ]);

        $user = User::find($userId);

        if (!$user) {
            return response("Not Found", 404);
        }

        if(strcmp($token, $user->password_reset_token) == 0) {
            $user->password = Hash::make($validated['password']);
            $user->password_reset_token = "";
            $user->save();
            return response(['message' => "password was changed"], 200);
        }
        return response(['message' => "token not match"], 403);

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
            return response(['message' => "logout"], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response(['error' => $e->getMessage()], 401);
        }
    }
}
