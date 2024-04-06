<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'unit_types';

    protected $fillable = [
      'code',
      'desc_en',
      'desc_ar'
    ];
}
