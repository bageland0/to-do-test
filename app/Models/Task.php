<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'todo_id',
        'user_id',
        'text',
        'image',
        'thumbnail',
    ];

    public function tags() : HasMany
    {
        return $this->hasMany(Tag::class);
    }
}
