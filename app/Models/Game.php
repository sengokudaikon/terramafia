<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Game
 * @property int $player_seat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Game newModelQuery()
 * @method static Builder|Game newQuery()
 * @method static Builder|Game query()
 * @method static Builder|Game whereCreatedAt($value)
 * @method static Builder|Game whereUpdatedAt($value)
 * @package App\Models
 */
class Game extends Model
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
}
