<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Room;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'custodian_id',
        'room_id',
        'password_updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_updated_at' => 'datetime',
        ];
    }

    /**
     * Get the valid roles for the user.
     *
     * @return array<string>
     */
    public static function validRoles(): array
    {
        return ['Admin', 'Viewer'];
    }

    /**
     * Check if the user is an Admin (custodian).
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    /**
     * Check if the user is a Viewer.
     *
     * @return bool
     */
    public function isViewer(): bool
    {
        return $this->role === 'Viewer';
    }

    /**
     * Get the user's room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Update the user's password.
     *
     * @param  string  $password
     * @return void
     */
    public function updatePassword(string $password): void
    {
        $this->password = Hash::make($password);
        $this->password_updated_at = now();
        $this->save();
    }
}
