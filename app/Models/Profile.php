<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['name', 'email', 'room_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}