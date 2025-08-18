<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'room_id',
        'category_id',
        'description',
        'last_checked_at',
        'quantity',
        'qr_code',
        'purchase_date',
        'purchase_price',
        'warranty_expires',
        'condition',
    ];

    public function units()
    {
        return $this->hasMany(ItemUnit::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
