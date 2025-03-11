<?php


namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {

        try{
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                   Auth::login($user);
                   return redirect()->route('dashboard');
               } else {
                   $userData = User::create([
                       'name' => $googleUser->name,
                       'email' => $googleUser->email,
                       'password' => Hash::make('password'),
                       'google_id' => $googleUser->id,
                   ]);

                   if ($userData) {
                       Auth::login($userData);
                       return redirect()->route('dashboard');
                   }

            }

           dd($googleUser);
           // Handle user information after successful authentication

        }catch(\Exception $e){
            return redirect()->route('login')->with('error', 'Something went wrong');
        }

    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();
        // Handle user information after successful authentication
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        // Handle user information after successful authentication
    }
}
