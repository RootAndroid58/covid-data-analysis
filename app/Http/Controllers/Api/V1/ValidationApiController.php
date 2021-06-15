<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\ApiResponser;
use App\Http\Helpers\ApiHelper;
use App\Models\User;
use Auth;

class ValidationApiController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email',$request->input('email'))->first();
        if($user->password == null){
            return response()->json(ApiHelper::SuccessorFail(403,['error' =>'Password Not Created Create new password in profile page!']));
        }

        if (!Auth::attempt($attr)) {
            return response()->json(ApiHelper::SuccessorFail(403,['error' =>'Credentials not match']));
        }
        auth()->user()->tokens()->where('name','API Token')->delete();

        $Bearer = auth()->user()->createToken('API Token')->plainTextToken;

        return $this->success([
            'token' => $Bearer,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout()
    {
        if(auth()->user()){
            auth()->user()->tokens()->delete();
            return ApiHelper::SuccessorFail(200,['message' => 'Tokens Revoked']);
        }

        return ApiHelper::SuccessorFail(200,['message' => 'Already logged out!']);
    }
}
