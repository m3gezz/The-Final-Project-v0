<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'owner',
        'content',
    ];

    protected $casts = [
        'owner' => 'array',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
