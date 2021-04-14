<?php

namespace App\Models;

use App\Enums\Death;
use App\Enums\GameRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Class Player
 * @property string $name
 * @property Game|null $game
 *
 * @package App\Models
 */
class Player extends Model
{
    use HasFactory, UUIDable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personal(): HasOne
    {
        return $this->hasOne(PlayerPersonal::class);
    }

    public function club(): BelongsToMany
    {
        return $this->belongsToMany(Club::class);
    }

    public function latest(): HasMany
    {
        return $this->hasMany(Game::class)->latest('id');
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function first(): HasMany
    {
        return $this->hasMany(Game::class)->where('serial', 1);
    }

    public function ingame(): HasOne
    {
        return $this->hasOne(GamePlayer::class);
    }

    public function rating()
    {
        return $this->hasOne(PlayerRating::class);
    }

    public function gameRatings()
    {
        return $this->hasMany(GamePlayerRating::class);
    }

    public function killNight(bool $isFirst): Player
    {
        $this->ingame()->is_killed = true;
        $this->ingame()->death = Death::Killed;

        if ($isFirst) {
            $this->first_bloods++;
        }

        $this->save();
        return $this;
    }

    public function killDay(): Player
    {
        $this->ingame()->is_killed = true;
        $this->ingame()->death = Death::Vote;

        $this->save();
        return $this;
    }

    public function remove(): Player
    {
        $this->ingame()->is_killed = true;
        $this->ingame()->death = Death::Abandon;

        $this->save();
        return $this;
    }
}
