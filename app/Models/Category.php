<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
    use HasFactory ,HasApiTokens , Notifiable  ;
    protected $fillable = [
        'name',
        'slug'
    ];

    public function posts(){
        return $this->HasMany(Post::class);
    }

}
