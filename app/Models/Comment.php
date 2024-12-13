<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'author_name',
        'author_email',
        'content',
        'created_at',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
