<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function members() {
        return $this->hasMany(ProjectMember::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
