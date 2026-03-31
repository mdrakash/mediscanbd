<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->first();

            if (! $user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                if (! $user) {
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                } else {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            } else {
                $user->update([
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            $token = $user->createToken('mediscan-token')->plainTextToken;

            return redirect(env('APP_FRONTEND_URL').'/auth/callback?token='.$token.'&user='.urlencode(json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ])));
        } catch (Throwable $th) {
            report($th);

            return redirect(env('APP_FRONTEND_URL').'/auth/callback?error=auth_failed');
        }
    }
}
