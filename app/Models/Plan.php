<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'sagment_prices';
   protected $fillable = [
    'sagment_id',
    'name',
    'prices',
];
}
