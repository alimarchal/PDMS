<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    use HasFactory;

    protected $fillable = ['prisoner_id', 'prison', 'city', 'region', 'type', 'date', 'description', 'attachment'];


    public function scopeSearchString(Builder $query, $search): Builder
    {
        return $query->where('prison', 'LIKE', '%' . $search . '%')->
        orWhere('prisoner_id', 'LIKE', '%' . $search . '%')->
        orWhere('prison', 'LIKE', '%' . $search . '%')->
        orWhere('city', 'LIKE', '%' . $search . '%')->
        orWhere('region', 'LIKE', '%' . $search . '%')->
        orWhere('type', 'LIKE', '%' . $search . '%')->
        orWhere('date', 'LIKE', '%' . $search . '%')->
        orWhere('description', 'LIKE', '%' . $search . '%');
    }


    public function scopeSearchAssistance(Builder $query, $search): Builder
    {
        $dateS = Carbon::now()->subMonth(3);
        $dateE = Carbon::now();
        return $query->where('type','Legal Assistance')->orWhere('type','=','Counselor Access')->whereBetween('date', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')]);
    }


}
