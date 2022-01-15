<?php

declare(strict_types=1);

namespace Easy\User\Services\Management;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Easy\User\Contracts\Management\AuthenticateInterface;

/**
 * @Authenticate
 */
class Authenticate implements AuthenticateInterface
{

    /**
     * @var null|Request $request
     */
    private ?Request $request = null;

    /**
     * @var array
     */
    private array $inputs = [];

    /**
     * @inheritDoc
     */
    public function authenticate(array $inputs, Request $request, string $guard = 'web'): ?Authenticatable
    {
        $this->request = $request;
        $this->inputs = $inputs;

        $this->ensureIsNotRateLimited();

        $guard = Auth::guard($guard);
        if (!$guard->attempt(['email' => $this->inputs['email'], 'password' => $this->inputs['password']], (boolean)$this->inputs['remember'])) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);

        }

        RateLimiter::clear($this->throttleKey());

        return $guard->user();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this->request));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    private function throttleKey(): string
    {
        return Str::lower($this->inputs['email']) . '|' . $this->request->ip();
    }

    /**
     * @inheritDoc
     */
    public function registration ($inputs, string $guard = 'web', string $model = User::class): ?Authenticatable
    {
        $user = $model::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => Hash::make($inputs['password']),
        ]);

        event(new Registered($user));

        Auth::guard($guard)->login($user);

        return Auth::guard($guard)->user();
    }


    /**
     * @param string $guard
     * @param Request $request
     * @return Authenticatable|null
     */
    public function destroy(string $guard, Request $request): ?Authenticatable
    {
        $auth = Auth::guard($guard);

        $user = $auth->user();

        $auth->logout();

//        $request->session()->invalidate();

//        $request->session()->regenerateToken();

        return $user;
    }
}
