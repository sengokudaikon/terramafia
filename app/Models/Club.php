<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Club
 * @property string $name
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @package App\Models
 */
class Club extends Model
{
    use HasFactory, UUIDable;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function currentSeason(): HasMany
    {
        return $this->hasMany(ClubGameSeason::class)->latest('id');
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(ClubGameSeason::class);
    }
}
