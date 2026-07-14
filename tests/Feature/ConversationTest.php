<?php

use App\Models\Conversation;
use App\Models\Message;
use App\Models\PeerRequest;
use App\Models\User;

test('guest cannot access conversations', function () {
    $this->get(route('conversations.index'))->assertRedirect(route('login'));
});

test('user can view their conversations', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    PeerRequest::factory()->create([
        'sender_id' => $user->id,
        'receiver_id' => $other->id,
        'status' => 'accepted',
    ]);

    Conversation::factory()->create([
        'user_one_id' => $user->id,
        'user_two_id' => $other->id,
    ]);

    $response = $this->actingAs($user)->get(route('conversations.index'));
    $response->assertOk();
});

test('user can start conversation with a peer', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    PeerRequest::factory()->create([
        'sender_id' => $user->id,
        'receiver_id' => $other->id,
        'status' => 'accepted',
    ]);

    $response = $this->actingAs($user)->get(route('conversations.start', $other));

    $response->assertRedirect();
});

test('user can send a message in their conversation', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    PeerRequest::factory()->create([
        'sender_id' => $user->id,
        'receiver_id' => $other->id,
        'status' => 'accepted',
    ]);

    $conversation = Conversation::factory()->create([
        'user_one_id' => $user->id,
        'user_two_id' => $other->id,
    ]);

    $response = $this->actingAs($user)->post(route('conversations.send', $conversation), [
        'body' => 'Hello there!',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'sender_id' => $user->id,
        'body' => 'Hello there!',
    ]);
});

test('user cannot view conversation they are not part of', function () {
    $user = User::factory()->create();
    $other1 = User::factory()->create();
    $other2 = User::factory()->create();
    $conversation = Conversation::factory()->create([
        'user_one_id' => $other1->id,
        'user_two_id' => $other2->id,
    ]);

    $response = $this->actingAs($user)->get(route('conversations.show', $conversation));
    $response->assertStatus(403);
});

test('conversation shows messages', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    PeerRequest::factory()->create([
        'sender_id' => $user->id,
        'receiver_id' => $other->id,
        'status' => 'accepted',
    ]);

    $conversation = Conversation::factory()->create([
        'user_one_id' => $user->id,
        'user_two_id' => $other->id,
    ]);

    $message1 = Message::factory()->create([
        'conversation_id' => $conversation->id,
        'sender_id' => $user->id,
        'body' => 'Message from user',
    ]);
    $message2 = Message::factory()->create([
        'conversation_id' => $conversation->id,
        'sender_id' => $other->id,
        'body' => 'Message from other',
    ]);

    $response = $this->actingAs($user)->get(route('conversations.show', $conversation));
    $response->assertOk();
    $response->assertSee('Message from user');
    $response->assertSee('Message from other');
});
