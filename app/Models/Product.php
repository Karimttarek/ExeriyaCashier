<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    // public $timestamps = true;

    protected $fillable = [
        'uuid',
        'item_code',
        'name',
        'name_ar',
    ];

    protected $hidden = [
        'code_type',
        'codeUsageRequestId',
        'parent_code',
        'item_code',
        'description',
        'description_ar',
        'type_code',
        'type_desc',
        'item_type',
        'category_id',
        'category_name',
        'purchase_price',
        'sell_price',
        'currency_code',
        'currency_desc',
        'tax_code',
        'tax',
        'discount',
        'stock',
        'active',
        'active_from',
        'active_to',
        'request_reason',
        'entry',
        'ported',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function invoicedetails() : BelongsTo
    {
        return $this->belongsTo(InvoicesDetails::class ,'item_uuid');
    }
}
