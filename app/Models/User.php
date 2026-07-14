<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @method bool isPeerWith(User $other)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'faculty',
        'semester',
        'student_id',
        'profile_picture',
        'bio',
        'role',
        'department',
        'phone_number',
        'date_of_birth',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth'     => 'date',
            'password'          => 'hashed',
        ];
    }

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function conversations()
    {
        return Conversation::query()
            ->where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id'); // standardized: was 'user_id'
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    // Peer requests
    public function sentRequests()
    {
        return $this->hasMany(PeerRequest::class, 'sender_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(PeerRequest::class, 'receiver_id');
    }

    // Check if this user is peer with another user
    public function isPeerWith(User $other): bool
    {
        return \App\Models\PeerRequest::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($other) {
                $q->where('sender_id', $this->id)
                  ->where('receiver_id', $other->id)
                  ->orWhere(function ($q) use ($other) {
                      $q->where('sender_id', $other->id)
                        ->where('receiver_id', $this->id);
                  });
            })
            ->exists();
    }
}
