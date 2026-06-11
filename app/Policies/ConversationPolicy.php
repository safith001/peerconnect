<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    protected function isParticipant(User $user, Conversation $conversation): bool
    {
        return $conversation->user_one_id === $user->id
            || $conversation->user_two_id === $user->id;
    }

    public function view(User $user, Conversation $conversation): bool
    {
        return $this->isParticipant($user, $conversation);
    }

    public function sendMessage(User $user, Conversation $conversation): bool
    {
        return $this->isParticipant($user, $conversation);
    }

    public function delete(User $user, Conversation $conversation): bool
    {
        // usually only allow if participant; tighten if needed
        return $this->isParticipant($user, $conversation);
    }
}
