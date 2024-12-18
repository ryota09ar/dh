<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmedYearMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
    ];

    /**
     * @param $year
     * @param $month
     * @return bool
     */
    public static function is_confirmed($year, $month): bool
    {
        return self::where('year', $year)->where('month', $month)->exists();
    }
}
