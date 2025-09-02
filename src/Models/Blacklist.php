<?php

namespace Kamoca\JwtDatabaseBlacklist\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $table = 'jwt_blacklists';

    protected $fillable = [
        'key',
        'value',
        'expired_at',
    ];
}
