<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Flasher\Laravel\Facade\Flasher;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // // ✅ Email verification পাঠানো হবে, কিন্তু বাধ্যতামূলক না রেজিস্ট্রেশনের সময়
        // $user->sendEmailVerificationNotification();

        Flasher::addSuccess('Registration successful! Please login.');

        return redirect(route('login'));
    }
}


//         event(new Registered($user));

//         Auth::login($user);

//         return redirect(route('login', absolute: false));
//     }
// }
