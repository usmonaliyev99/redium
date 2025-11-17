<?php

namespace Usmonaliyev\Redium\Data;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;

class Record
{
    private Authenticatable $auth;

    private string $target;

    private string $name;

    private string $token;

    private array $abilities;

    private array $payload;

    private ?Carbon $expiresAt;

    public static function with(): Record
    {
        return new self;
    }

    public function setTarget(string $target): Record
    {
        $this->target = $target;
        $this->auth = new $target;

        return $this;
    }

    public function setName(string $name): Record
    {
        $this->name = $name;

        return $this;
    }

    public function setToken(string $token): Record
    {
        $this->token = $token;

        return $this;
    }

    public function setAbilities(array|string $abilities): Record
    {
        if (is_string($abilities)) {
            $abilities = json_decode($abilities, true);
        }

        $this->abilities = $abilities;

        return $this;
    }

    public function setPayload(array|string $payload): Record
    {
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        $this->payload = $payload;
        $this->auth->fill($payload);

        return $this;
    }

    public function setExpiresAt(?string $expiresAt = null): Record
    {
        if (is_null($expiresAt)) {
            $this->expiresAt = null;

            return $this;
        }

        $this->expiresAt = Carbon::parse($expiresAt);

        return $this;

    }

    public function getAuth(): ?Authenticatable
    {
        return $this->auth;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }
}
