<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    const OPEN = 1;
    const CLOSED = 0;

    /**
     * relation User
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * relation Comment
     *
     * @return void
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Blog public or closed.
     *
     * @param [type] $query
     * @return void
     */
    public function scopeOpen($query)
    {
        return $query->where('status', self::OPEN);
    }

    /**
     * Blog is closed.
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->status == self::CLOSED;
    }
}
