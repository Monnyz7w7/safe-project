<?php

namespace App\Http\Controllers\Socialite;

use Illuminate\Http\Request;
use App\Factories\CreateUserFactory;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {
        return view('socialite.login');
    }

    public function redirect(string $service)
    {
        return Socialite::driver($service)->redirect();
    }

    public function callback(string $service)
    {
        $user = app(CreateUserFactory::class)
            ->forService($service)
            ->create(Socialite::driver($service)->user());

        auth()->login($user);

        if ($user->wasRecentlyCreated) {
            // Fire an event here
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
