<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use Socialite;
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
                if($userSocial->getEmail() != null){

                    $user = User::create([
                        'name'          => $userSocial->getName(),
                        'email'         => $userSocial->getEmail(),
                        'image'         => $userSocial->getAvatar(),
                        'provider_id'   => $userSocial->getId(),
                        'provider'      => $provider,
                        'email_verified_at'      => Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), now())->format('Y-m-d H:i:s'),

                    ]);
                    $user->roles()->sync(2);
                    Auth::login($user);
                }else{
                    redirect()->route('login')->withErrors('error','There was an issue while creating an account try again or use other account to login!');
                }
            }

         return redirect()->route('home');
        }
    }
}
