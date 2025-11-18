<?php

namespace Usmonaliyev\Redium\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Usmonaliyev\Redium\Data\AccessToken;
use Usmonaliyev\Redium\Data\Record;
use Usmonaliyev\Redium\Redium;

trait HasRediumTokens
{
    /**
     * The access token the user is using for the current request.
     *
     * @var Redium
     */
    protected $accessToken;

    /**
     * Determine if the current API token has a given scope.
     */
    public function tokenCan(string $ability): bool
    {
        return $this->accessToken && $this->accessToken->can($ability);
    }

    /**
     * Determine if the current API token does not have a given scope.
     */
    public function tokenCant(string $ability): bool
    {
        return ! $this->tokenCan($ability);
    }

    /**
     * Create a new personal access token for the user.
     */
    public function createToken(string $name, array $abilities = ['*'], ?Carbon $expiresAt = null): AccessToken
    {
        $plainTextToken = $this->generateTokenString();

        $record = Record::with()
            ->setName($name)
            ->setAbilities($abilities)
            ->setExpiresAt($expiresAt)
            ->setToken(hash('sha256', $plainTextToken))
            ->setTarget(get_class($this))
            ->setPayload($this->toJson());

        $token = Redium::create($record);

        return new AccessToken($token, $plainTextToken);
    }

    /**
     * Get the access tokens that belong to model.
     *
     * @return array<Redium>
     */
    public function tokens(): array
    {
        return Redium::forUser($this->getKey());
    }

    /**
     * Generate the token string.
     */
    public function generateTokenString(): string
    {
        return sprintf('%s%s', $tokenEntropy = Str::random(40), hash('crc32b', $tokenEntropy));
    }

    /**
     * Get the access token currently associated with the user.
     */
    public function currentAccessToken(): Redium
    {
        return $this->accessToken;
    }

    /**
     * Set the current access token for the user.
     *
     * @return $this
     */
    public function withAccessToken(Redium $redium): self
    {
        $this->accessToken = $redium;

        return $this;
    }
}
