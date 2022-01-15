<?php

namespace Easy\User\Services;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @Authenticate
 */
class Authenticate
{
    /**
     * @var null|Request $request
     */
    private $request = null;

    /**
     * @var array
     */
    private $inputs = [];

    /**
     * @param array $inputs
     * @param mixed $request
     */
    public function __construct(array $inputs, Request $request)
    {
        $this->request = $request;
        $this->inputs = $inputs;
    }

    /**
     * @return Authenticatable|null
     * @throws ValidationException
     */
    public function authenticate(): ?Authenticatable
    {
        $this->ensureIsNotRateLimited();

        $guard = Auth::guard(config('sanctum.guard[0]', 'web'));
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
    public function ensureIsNotRateLimited()
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
    public function throttleKey(): string
    {
        return Str::lower($this->inputs['email']) . '|' . $this->request->ip();
    }
}
