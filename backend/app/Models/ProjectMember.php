<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectMemberFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'invited_by'
    ];

    protected $casts = [
        'invited_by' => 'array',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
