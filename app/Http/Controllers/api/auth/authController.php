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
        User::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
        ]);       
        $credentials = request(['phone', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = User::where('phone',$request->phone)->first();
        $user->token ='bearer' . $token;
        return response()->json(['success'=>'true','user'=>$user,'token'=>$token],200);
    }

}
