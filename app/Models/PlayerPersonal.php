<?php

namespace App\Models;

use App\VO\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class PlayerPersonal
 *
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $patronymic
 * @property Gender|null $gender
 * @property Carbon|null $birthday
 * @property string|null $phone
 * @package App\Models
 */
class PlayerPersonal extends Model
{
    use HasFactory;

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
