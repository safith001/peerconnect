<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Query scope to list conversations involving a particular user
    public function scopeInvolves($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_one_id', $userId)
              ->orWhere('user_two_id', $userId);
        });
    }

    // Who is the other participant compared to $authUserId
    public function otherParticipant(int $authUserId)
    {
        return $this->user_one_id === $authUserId ? $this->userTwo : $this->userOne;
    }
}
