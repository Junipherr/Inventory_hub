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
        'quantity',
        'description',
        'last_checked_at',
    ];

    public function units()
    {
        return $this->hasMany(ItemUnit::class);
    }

    protected static function booted()
    {
        static::created(function ($item) {
            $item->syncUnits();
        });

        static::updated(function ($item) {
            $item->syncUnits();
        });
    }

    public function syncUnits()
    {
        $existingUnitsCount = $this->units()->count();
        $unitsToCreate = $this->quantity - $existingUnitsCount;

        if ($unitsToCreate > 0) {
            for ($i = $existingUnitsCount + 1; $i <= $this->quantity; $i++) {
                $this->units()->create([
                    'unit_number' => $i,
                    'last_checked_at' => null,
                ]);
            }
        } elseif ($unitsToCreate < 0) {
            // Remove extra units if quantity decreased
            $unitsToRemove = abs($unitsToCreate);
            $units = $this->units()->orderByDesc('unit_number')->take($unitsToRemove)->get();
            foreach ($units as $unit) {
                $unit->delete();
            }
        }
    }
}
