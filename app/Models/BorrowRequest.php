<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'purpose',
        'return_date',
        'status',
        'approved_at',
        'returned_at',
        'admin_notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'returned' => 'badge-info',
            'overdue' => 'badge-dark',
            default => 'badge-secondary',
        };
    }

    public function getStatusText()
    {
        return ucfirst($this->status);
    }

    public function isOverdue()
    {
        return $this->status === 'approved' && 
               $this->return_date < now() && 
               $this->status !== 'returned';
    }
}
