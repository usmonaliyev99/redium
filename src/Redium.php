<?php

namespace Usmonaliyev\Redium;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Redis\Connection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use JsonSerializable;
use Usmonaliyev\Redium\Data\Record;

class Redium implements Arrayable, JsonSerializable
{
    private Authenticatable $auth;

    private string $token;

    private ?Carbon $expiresAt;

    private string $name;

    private array $abilities;

    public function __construct(Record $record)
    {
        $this->auth = $record->getAuth();
        $this->token = $record->getToken();
        $this->name = $record->getName();
        $this->abilities = $record->getAbilities();
        $this->expiresAt = $record->getExpiresAt();
    }

    /**
     * Get then Redis connection
     */
    public static function redis(): Connection
    {
        return Redis::connection(config('redium.connection'));
    }

    /**
     * Create a new token.
     */
    public static function create(Record $record): Redium
    {
        $instance = new static($record);

        $instance->save();

        return $instance;
    }

    /**
     * Create array for store Redis.
     */
    public function toRedis(): array
    {
        return [
            'name' => $this->name,
            'target' => get_class($this->auth),
            'payload' => $this->auth->toJson(),
            'abilities' => json_encode($this->abilities),
            'expires_at' => $this->expiresAt?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Save the token to Redis.
     */
    public function save(): void
    {
        $key = "tokens:{$this->token}";
        $data = flatter($this->toRedis());

        self::redis()->hset($key, ...$data);

        if (isset($this->expiresAt)) {
            $ttl = $this->expiresAt->diffInSeconds(absolute: true);

            self::redis()->expire($key, $ttl);
        }

        self::redis()->sadd("users:{$this->auth->getKey()}:tokens", $this->token);
    }

    /**
     * Get authenticated user
     */
    public function getAuth(): ?Authenticatable
    {
        return $this->auth;
    }

    /**
     * Find a token by hashed token.
     */
    public static function findByToken(string $plainTextToken): ?Redium
    {
        $token = hash('sha256', $plainTextToken);

        $state = self::redis()->hgetall("tokens:{$token}");
        if (empty($state)) {
            return null;
        }

        $record = Record::with()
            ->setToken($token)
            ->setName($state['name'])
            ->setTarget($state['target'])
            ->setPayload($state['payload'])
            ->setAbilities($state['abilities'])
            ->setExpiresAt($state['expires_at']);

        return new self($record);
    }

    /**
     * Get all tokens for a user.
     */
    public static function forUser(int $id): array
    {
        $hashedTokens = self::redis()->smembers("users:{$id}:tokens");

        $tokens = [];
        foreach ($hashedTokens as $token) {
            $state = self::redis()->hgetall("tokens:{$token}");
            if (empty($state)) {
                continue;
            }

            $record = Record::with()
                ->setToken($token)
                ->setName($state['name'])
                ->setTarget($state['target'])
                ->setPayload($state['payload'])
                ->setAbilities($state['abilities'])
                ->setExpiresAt($state['expires_at']);

            $tokens[] = new self($record);
        }

        return $tokens;
    }

    /**
     * Delete the token.
     */
    public function delete(): void
    {
        self::redis()->srem("users:{$this->auth->getKey()}:tokens", $this->token);

        self::redis()->del("tokens:{$this->token}");
    }

    /**
     * Determine if the token has a given ability.
     */
    public function can(string $ability): bool
    {
        return in_array('*', $this->abilities) || array_key_exists($ability, array_flip($this->abilities));
    }

    /**
     * Determine if the token is missing a given ability.
     */
    public function cant(string $ability): bool
    {
        return ! $this->can($ability);
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'user' => $this->auth,
            'token' => $this->token,
            'abilities' => $this->abilities,
            'expires_at' => $this->expiresAt?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
