<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Google ile giriş — paket bağımlılığı olmadan standart OAuth 2.0 akışı.
 * GOOGLE_CLIENT_ID ve GOOGLE_CLIENT_SECRET .env'de tanımlı olmalıdır.
 */
class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        abort_unless(config('services.google.client_id'), 404);

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => route('google.callback'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    public function callback(Request $request)
    {
        abort_unless(config('services.google.client_id'), 404);

        // State kontrolü (CSRF koruması)
        if (! $request->filled('code') || $request->state !== $request->session()->pull('google_oauth_state')) {
            return redirect()->route('login')->with('error', 'Google ile giriş başarısız oldu, lütfen tekrar deneyin.');
        }

        try {
            // Kodu access token ile değiştir
            $token = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'code' => $request->code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => route('google.callback'),
            ])->json();

            if (empty($token['access_token'])) {
                throw new \RuntimeException('Access token alınamadı.');
            }

            // Kullanıcı bilgilerini al
            $googleUser = Http::withToken($token['access_token'])
                ->get('https://www.googleapis.com/oauth2/v3/userinfo')
                ->json();

            if (empty($googleUser['sub']) || empty($googleUser['email'])) {
                throw new \RuntimeException('Kullanıcı bilgisi alınamadı.');
            }
        } catch (\Throwable $e) {
            Log::warning('Google OAuth hatası: '.$e->getMessage());

            return redirect()->route('login')->with('error', 'Google ile giriş sırasında bir sorun oluştu.');
        }

        // Önce google_id ile, yoksa e-posta ile eşleştir; ikisi de yoksa yeni üye oluştur
        $user = User::where('google_id', $googleUser['sub'])->first()
            ?? User::where('email', $googleUser['email'])->first();

        if ($user) {
            if (! $user->google_id) {
                $user->update(['google_id' => $googleUser['sub']]);
            }
        } else {
            $user = User::create([
                'name' => $googleUser['name'] ?? $googleUser['email'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['sub'],
                'password' => Hash::make(Str::random(40)),
                'is_admin' => false,
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('account.index'));
    }
}
