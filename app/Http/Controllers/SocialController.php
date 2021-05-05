<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Auth;

class SocialController extends Controller
{
    public function redirect($provider)
    {
     return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $userSocial =   Socialite::driver($provider)->user();
        $users      =   User::where(['email' => $userSocial->getEmail()])->first();

        if($users){
            Auth::login($users);
            return redirect('/');
        }else{
            $users      =   User::where(['email' => $userSocial->getEmail()])->withTrashed()->first();
            if(isset($users) && $users->deleted_at != null){
                User::where('id',$users->id)->withTrashed()->restore();
                Auth::login($users);
            }else{
                $user = User::create([
                    'name'          => $userSocial->getName(),
                    'email'         => $userSocial->getEmail(),
                    'image'         => $userSocial->getAvatar(),
                    'provider_id'   => $userSocial->getId(),
                    'provider'      => $provider,
                ]);
                $user->roles()->sync(2);
                Auth::login($user);
            }

         return redirect()->route('home');
        }
    }
}
