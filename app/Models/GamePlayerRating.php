<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GamePlayerRating
 * @property int $points
 * @property bool $first_blood
 * @property bool $victory_red
 * @property bool $victory_black
 * @property bool $victory_don
 * @property bool $victory_sheriff
 * @property bool $victory_guessing
 * @property bool $full_checks
 * @property bool $sheriff_fb
 * @property bool $death_wish2
 * @property bool $death_wish3
 * @property bool $withdrawal
 * @package App\Models
 */
class GamePlayerRating extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
