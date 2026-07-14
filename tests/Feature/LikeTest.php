<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

test('guest cannot like a post', function () {
    $post = Post::factory()->create();
    $this->post(route('posts.like', $post))->assertRedirect(route('login'));
});

test('authenticated user can like a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    $response = $this->actingAs($user)->post(route('posts.like', $post));

    $response->assertRedirect();
    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);
});

test('liking toggles (unlike on second click)', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    $this->actingAs($user)->post(route('posts.like', $post));
    $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'post_id' => $post->id]);

    $this->actingAs($user)->post(route('posts.like', $post));
    $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'post_id' => $post->id]);
});

test('post shows like count', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    Like::factory(3)->for($post)->create();

    $this->assertEquals(3, $post->fresh()->likes->count());
});
