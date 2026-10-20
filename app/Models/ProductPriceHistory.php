<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//cambios de precio guardados en el historial de un producto x
class ProductPriceHistory extends Model
{
    protected $table = 'product_price_history';
    protected $fillable = [
        'product_id',
        'old_price',
        'new_price',
        'changed_by',
        'changed_at',
        
    ];
    

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function changedBy(){
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getDifferenceAttribute(): float{
        return $this->new_price - $this->old_price;
    }
}