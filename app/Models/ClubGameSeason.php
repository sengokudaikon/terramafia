<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class ClubGameSeason
 * @property string|null $serial
 * @property Carbon|null $started_at
 * @property Carbon|null $ends_at
 * @package App\Models
 */
class ClubGameSeason extends Model
{
    use HasFactory;

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
