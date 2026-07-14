<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

test('guest cannot comment', function () {
    $post = Post::factory()->create();
    $this->post(route('comments.store', $post))->assertRedirect(route('login'));
});

test('authenticated user can comment on a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    $response = $this->actingAs($user)->post(route('comments.store', $post), [
        'body' => 'This is a test comment.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'This is a test comment.',
    ]);
});

test('comment requires body', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    $response = $this->actingAs($user)->post(route('comments.store', $post), []);

    $response->assertSessionHasErrors('body');
});

test('owner can delete their comment', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    $comment = Comment::factory()->for($post)->for($user)->create();

    $response = $this->actingAs($user)->delete(route('comments.destroy', $comment));

    $response->assertRedirect();
    $this->assertModelMissing($comment);
});

test('non-owner cannot delete a comment', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $post = Post::factory()->for($owner)->create();
    $comment = Comment::factory()->for($post)->for($owner)->create();

    $response = $this->actingAs($other)->delete(route('comments.destroy', $comment));

    $response->assertStatus(403);
    $this->assertModelExists($comment);
});
