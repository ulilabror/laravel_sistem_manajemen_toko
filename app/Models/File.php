<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename', 'path', 'uploaded_by', 'related_id', 'related_type'
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'uploaded_by');
    // }

    public function related()
    {
        return $this->morphTo();
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
