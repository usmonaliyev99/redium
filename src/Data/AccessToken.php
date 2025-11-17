<?php

namespace Usmonaliyev\Redium\Data;

use Illuminate\Contracts\Support\Arrayable;
use Usmonaliyev\Redium\Redium;

class AccessToken implements \JsonSerializable, Arrayable
{
    /**
     * The access token instance.
     */
    public Redium $accessToken;

    /**
     * The plain text version of the token.
     */
    public string $plainTextToken;

    /**
     * Create a new access token result.
     */
    public function __construct(Redium $accessToken, string $plainTextToken)
    {
        $this->accessToken = $accessToken;
        $this->plainTextToken = $plainTextToken;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'accessToken' => $this->accessToken,
            'plainTextToken' => $this->plainTextToken,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
