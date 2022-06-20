<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrisionerShifted extends Model
{
    use HasFactory;

    public $fillable = [
        'prisoner_id',
        'shifted_to_other_department',
        'shifting_date_gregorian',
    ];
}
