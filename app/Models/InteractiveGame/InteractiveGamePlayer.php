<?php

namespace App\Models\InteractiveGame;

use App\Enums\Death;
use App\Enums\GameRole;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InteractiveGameStagePlayer
 * @property int $seat
 * @property bool $is_killed
 * @property bool $first_blood;
 * @property Death $death
 * @property string|null $death_reason
 * @property GameRole $gameRole;
 * @property array $death_wish;
 * @property array $sheriff_checks;
 * @property array $don_checks;
 *
 * @package App\Models
 */
class InteractiveGamePlayer extends Model
{
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
