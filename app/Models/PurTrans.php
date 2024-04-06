<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurTrans extends Model
{
    use HasFactory;

    protected $table = 'invoiceHead';
    // public $timestamps = true;
    protected $guarded = [];
}
