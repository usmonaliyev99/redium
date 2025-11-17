<?php

namespace Usmonaliyev\Redium\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard as Master;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Usmonaliyev\Redium\Redium;

class Guard implements Master
{
    use GuardHelpers;

    /**
     * The request instance.
     */
    protected Request $request;

    /**
     * Set the current request instance.
     *
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Create a new authentication guard.
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * Set the current user.
     */
    public function setUser(Authenticatable $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the currently authenticated user.
     */
    public function user(): ?Authenticatable
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if (empty($token)) {
            return null;
        }

        $redium = Redium::findByToken($token);
        if (! $redium) {
            return null;
        }

        $user = $redium->getAuth();
        $this->user = $user->withAccessToken($redium);

        return $user;
    }

    /**
     * Validate a user's credentials.
     */
    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['token'])) {
            return false;
        }

        $redium = Redium::findByToken($credentials['token']);
        if (! $redium) {
            return false;
        }

        $user = $redium->getAuth();
        $this->user = $user->withAccessToken($redium);

        return ! is_null($this->user);
    }

    /**
     * Determine if the current user is authenticated.
     */
    public function check(): ?bool
    {
        $token = $this->request->bearerToken();
        if (empty($token)) {
            return null;
        }

        $redium = Redium::findByToken($token);
        if (! $redium) {
            return null;
        }

        $user = $redium->getAuth();
        $this->user = $user->withAccessToken($redium);

        return isset($this->user);
    }

    /**
     * Determine if the current user is a guest.
     */
    public function guest(): bool
    {
        return is_null($this->user);
    }

    /**
     * Get the ID for the currently authenticated user.
     */
    public function id(): int|string|null
    {
        if (isset($this->user)) {
            return $this->user->getKey();
        }

        $token = $this->request->bearerToken();
        if (empty($token)) {
            return null;
        }

        $redium = Redium::findByToken($token);
        if (! $redium) {
            return null;
        }

        $user = $redium->getAuth();
        $this->user = $user->withAccessToken($redium);

        return $this->user->getKey();
    }
}
