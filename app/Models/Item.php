<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'department',
        'category_id',
        'description',
        'last_checked_at',
        'quantity',
        'qr_code',
    ];

    public function units()
    {
        return $this->hasMany(ItemUnit::class);
    }
}
