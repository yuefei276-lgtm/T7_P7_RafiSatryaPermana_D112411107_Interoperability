<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Post -> table_name = posts
    // custome table name :
    // protected $table='table_name'

    // define cloumn name
    protected $fillable = array('title', 'content', 'status', 'user_id');

    // untuk melakukan update field created_at dan updated_at secara otomatis
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
