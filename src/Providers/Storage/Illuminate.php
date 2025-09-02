<?php

namespace Kamoca\JwtDatabaseBlacklist\Providers\Storage;

use Kamoca\JwtDatabaseBlacklist\Models\Blacklist;
use Str;
use Tymon\JWTAuth\Contracts\Providers\Storage;

class Illuminate implements Storage
{
    /**
     * The JWT blacklist model instance.
     *
     * @var \Kamoca\JwtDatabaseBlacklist\Models\Blacklist
     */
    protected $blacklist;

    /**
     * Constructor.
     *
     * @param \Kamoca\JwtDatabaseBlacklist\Models\Blacklist $blacklist
     */
    public function __construct(Blacklist $blacklist)
    {
        $this->blacklist = $blacklist;
    }

    /**
     * Add a new item into storage.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $minutes
     * @return void
     */
    public function add($key, $value, $minutes)
    {
        $this->blacklist->create([
            'key' => $key,
            'value' => $this->toString($value),
            'expires_at' => now()->addMinutes($minutes),
        ]);
    }

    /**
     * Add a new item into storage forever.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function forever($key, $value)
    {
        $this->blacklist->create([
            'key' => $key,
            'value' => $this->toString($value),
            'expires_at' => null,
        ]);
    }

    /**
     * Get an item from storage.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get($key)
    {
        $value = $this->blacklist->where('key', $key)->first()?->value;

        if (is_null($value)) {
            return null;
        }

        return $this->fromString($value);
    }

    /**
     * Remove an item from storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function destroy($key)
    {
        return $this->blacklist->where('key', $key)->delete();
    }

    /**
     * Remove all items associated with the tag.
     *
     * @return void
     */
    public function flush()
    {
        $this->blacklist->truncate();
    }

    /**
     * Convert the given value to a string.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function toString($value): string
    {
        return is_string($value) ? $value : json_encode($value);
    }

    /**
     * Convert the given value from a string.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function fromString($value): mixed
    {
        return Str::isJson($value) ? json_decode($value, true) : $value;
    }
}
