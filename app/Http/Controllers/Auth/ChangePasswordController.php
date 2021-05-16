<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        abort_if(Gate::denies('profile_password_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $token = auth()->user()->tokens()->where('name','API Token')->first();
        $Bearer = null;

        if($token != null){
            $Bearer = $token->id. "|".$token->token;
        }

        return view('auth.passwords.edit',compact('Bearer'));
    }

    public function update(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        if($user->password != null){
            $check = Auth::attempt(['email' => $user->email, 'password' => $request->old_password]);
            if($check){

                auth()->user()->update($request->validated());
                return redirect()->route('profile.password.edit')->with('message', __('global.change_password_success'));
            }else{
                // pass not match
                return redirect()->route('profile.password.edit')->with('error', "Credentials not match");
            }
        }

        auth()->user()->update($request->validated());
        return redirect()->route('profile.password.edit')->with('message', __('global.change_password_success'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        $user->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('global.update_profile_success'));
    }

    public function destroy()
    {
        $user = auth()->user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        $user->delete();

        return redirect()->route('login')->with('message', __('global.delete_account_success'));
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = auth()->user();

        if ($user->two_factor) {
            $message = __('global.two_factor.disabled');
        } else {
            $message = __('global.two_factor.enabled');
        }

        $user->two_factor = !$user->two_factor;

        $user->save();

        return redirect()->route('profile.password.edit')->with('message', $message);
    }

    public function genToken(Request $request)
    {
        $user = auth()->user();

        $user->tokens()->delete();

        $token = auth()->user()->createToken('API Token')->plainTextToken;

        return redirect()->back()->with('message','New Token successfully generated!');
    }
    public function removeToken(Request $request)
    {
        $user = auth()->user();

        $user->tokens()->delete();

        return redirect()->back()->with('message','Token successfully Removed!');
    }
}
