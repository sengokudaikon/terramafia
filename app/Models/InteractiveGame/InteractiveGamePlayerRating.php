<?php

namespace App\Models\InteractiveGame;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InteractiveInteractiveGamePlayerRating
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
class InteractiveGamePlayerRating extends Model
{
    public function interactiveGame()
    {
        return $this->belongsTo(InteractiveGame::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function calculate(array $params)
    {
        $checks = $params['sheriff_checks']['true'];
        if(count($checks) === 3){
            $this->full_checks = true;
        }

    }
}
