<?php

namespace App\Http\Controllers\api\auth;

use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{

    public function register(Request $request){
        $rules =[
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'], 
            'phone' =>['required', 'string', 'unique:users','numeric']
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $data = $request->all();
        $user = User::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
        ]); 
        $token = Auth::login($user);
        
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'phone' => 'required|string|numeric',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('phone', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    
    }

}
