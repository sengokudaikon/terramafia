<?php

namespace App\Models\InteractiveGame;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class InteractiveGame
 * @property int $player_seat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|InteractiveGame newModelQuery()
 * @method static Builder|InteractiveGame newQuery()
 * @method static Builder|InteractiveGame query()
 * @method static Builder|InteractiveGame whereCreatedAt($value)
 * @method static Builder|InteractiveGame whereUpdatedAt($value)
 * @package App\Models
 */
class InteractiveGame extends Model
{
    use HasFactory, UUIDable;

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(InteractiveGameStage::class);
    }
}
