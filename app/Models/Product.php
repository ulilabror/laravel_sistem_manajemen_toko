<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_type',
        'product_label',
        'product_sku',
        'product_barcode_id',
        'product_description',
        'price',
        'created_by',
    ];

    /**
     * Get the user that created the product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all of the files for the product.
     */
    public function files()
    {
        return $this->morphMany(File::class, 'related');
    }
}
