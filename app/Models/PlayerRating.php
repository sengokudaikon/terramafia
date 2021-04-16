<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerRating
 * @property int|null $first_bloods
 * @property int|null $total_games
 * @property int|null $victories
 * @property int|null $victory_red
 * @property int|null $victory_black
 * @property int|null $victory_don
 * @property int|null $victory_sheriff
 * @property int|null $victory_guessing
 * @property int|null $full_checks
 * @property int|null $sheriff_fb
 * @property int|null $death_wish
 * @property int|null $withdrawal
 * @package App\Models
 */
class PlayerRating extends Model
{
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
