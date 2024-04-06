<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoicesDetails extends Model
{
    use HasFactory;

    protected $table = 'invoicedetails';
    protected $guarded = [];

    public function product() : HasMany
    {
        return $this->hasMany(Product::class ,'uuid');
    }

}
