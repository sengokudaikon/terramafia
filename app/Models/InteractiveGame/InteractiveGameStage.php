<?php

namespace App\Models\InteractiveGame;

use App\Enums\GameRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InteractiveGameStage
 * @property int $serial
 * @property array $players_removed
 * @package App\Models
 */
class InteractiveGameStage extends Model
{
    use HasFactory;

    public function InteractiveGame(): BelongsTo
    {
        return $this->belongsTo(InteractiveGame::class);
    }

    public function sheriffCheck(InteractiveGamePlayer $sheriff, string $target): bool
    {
        /** @var Player $player */
        $player = $this->InteractiveGame()->where('player.uuid', $target);

        if ($player->inInteractiveGame()->InteractiveGameRole === InteractiveGameRole::Mafia || $player->inInteractiveGame()->InteractiveGameRole === InteractiveGameRole::Godfather) {
            $sheriff->sheriff_checks['true'] = $player->inInteractiveGame()->seat;

            return true;
        } else {
            $sheriff->sheriff_checks['false'] = $player->inInteractiveGame()->seat;

            return false;
        }
    }

    public function donCheck(InteractiveGamePlayer $don, string $target): bool
    {
        /** @var Player $player */
        $player = $this->InteractiveGame()->where('player.uuid', $target);

        return $player->inInteractiveGame()->InteractiveGameRole === InteractiveGameRole::Detective;
    }

    public function kill(string $id): void
    {
        /** @var Player $player */
        $player = $this->InteractiveGame()->where('player.uuid', $id);
        $fb = $this->serial === 1;

        $player->killNight($fb);
        $this->players_removed = $player->uuid();

        $this->save();
    }

    public function vote(array $ids): void
    {
        foreach ($ids as $id) {
            /** @var Player $player */
            $player = $this->InteractiveGame()->where('player.uuid', $id);
            $player->killDay();
            $this->players_removed = $player->uuid();
        }

        $this->save();
    }

    public function remove(array $ids): void
    {
        foreach ($ids as $id) {
            /** @var Player $player */
            $player = $this->InteractiveGame()->where('player.uuid', $id);
            $player->remove();
            $this->players_removed = $player->uuid();
        }

        $this->save();
    }
}
