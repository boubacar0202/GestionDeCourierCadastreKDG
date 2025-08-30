<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    //
    use HasFactory; 
    protected $fillable = [
        'txt_designationtri', 
    ];
}
