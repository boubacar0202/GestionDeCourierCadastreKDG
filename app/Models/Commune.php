<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    //
    protected $fillable = [
        'slt_commune',
    ];

    public function arrondissement()
    {
        return $this->belongsTo(Arrondissement::class);
    }
}
