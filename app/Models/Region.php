<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //
    protected $fillable = [
        'slt_region',
    ];

    protected $with = ['departements'];


    public function departements()
    {
        return $this->hasMany(Departement::class);
    }
}
