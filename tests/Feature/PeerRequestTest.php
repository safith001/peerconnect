<?php

use App\Models\PeerRequest;
use App\Models\User;

test('guest cannot access peer requests', function () {
    $this->get(route('peer_requests.index'))->assertRedirect(route('login'));
});

test('authenticated user can send a peer request', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $response = $this->actingAs($sender)->post(route('peer_requests.send', $receiver));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('peer_requests', [
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'status' => 'pending',
    ]);
});

test('cannot send peer request to self', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('peer_requests.send', $user));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('cannot send duplicate peer request', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $this->actingAs($sender)->post(route('peer_requests.send', $receiver));
    $response = $this->actingAs($sender)->post(route('peer_requests.send', $receiver));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('receiver can accept a peer request', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $request = PeerRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($receiver)->post(route('peer_requests.accept', $request));

    $response->assertRedirect();
    $this->assertDatabaseHas('peer_requests', [
        'id' => $request->id,
        'status' => 'accepted',
    ]);
});

test('only receiver can accept a request', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $other = User::factory()->create();
    $request = PeerRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($other)->post(route('peer_requests.accept', $request));

    $response->assertStatus(403);
});

test('receiver can decline a peer request', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $request = PeerRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($receiver)->post(route('peer_requests.decline', $request));

    $response->assertRedirect();
    $this->assertDatabaseHas('peer_requests', [
        'id' => $request->id,
        'status' => 'declined',
    ]);
});

test('accepted users are peers', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    PeerRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'status' => 'accepted',
    ]);

    expect($sender->isPeerWith($receiver))->toBeTrue();
    expect($receiver->isPeerWith($sender))->toBeTrue();
});

test('pending users are not peers', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    PeerRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'status' => 'pending',
    ]);

    expect($sender->isPeerWith($receiver))->toBeFalse();
});

test('declined users are not peers', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    PeerRequest::factory()->create([
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'status' => 'declined',
    ]);

    expect($sender->isPeerWith($receiver))->toBeFalse();
});
