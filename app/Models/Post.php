<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

protected $fillable =
['title','content','attachment','user_id'];

protected $casts = ['attachment' => 'array'];

public function user()
{
     return $this->belongsTo(User::class);
}
public function comments()
{
    return $this->hasMany(Comment::class);
}
}
