<?php

use App\Models\Post;
use App\Models\User;

test('guest cannot access posts', function () {
    $this->get(route('posts.index'))->assertRedirect(route('login'));
    $this->get(route('posts.create'))->assertRedirect(route('login'));
    $this->post(route('posts.store'))->assertRedirect(route('login'));
});

test('posts index shows all posts', function () {
    $user = User::factory()->create();
    $posts = Post::factory(3)->for($user)->create();

    $response = $this->actingAs($user)->get(route('posts.index'));

    $response->assertOk();
    foreach ($posts as $post) {
        $response->assertSee($post->title);
    }
});

test('posts show displays a single post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    $firstLine = explode("\n", $post->content)[0];

    $response = $this->actingAs($user)->get(route('posts.show', $post));

    $response->assertOk();
    $response->assertSee($post->title);
    $response->assertSee($firstLine);
});

test('authenticated user can create a post', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('posts.store'), [
        'title' => 'Test Post Title',
        'content' => 'This is the content of the test post.',
    ]);

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('posts', [
        'title' => 'Test Post Title',
        'content' => 'This is the content of the test post.',
        'user_id' => $user->id,
    ]);
});

test('post creation requires title and content', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('posts.store'), []);

    $response->assertSessionHasErrors(['title', 'content']);
});

test('owner can edit their post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    $response = $this->actingAs($user)->put(route('posts.update', $post), [
        'title' => 'Updated Title',
        'content' => 'Updated content.',
    ]);

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title',
        'content' => 'Updated content.',
    ]);
});

test('non-owner cannot edit a post', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $post = Post::factory()->for($owner)->create();

    $response = $this->actingAs($other)->put(route('posts.update', $post), [
        'title' => 'Hacked Title',
        'content' => 'Hacked content.',
    ]);

    $response->assertStatus(403);
});

test('owner can delete their post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

    $response->assertRedirect(route('posts.index'));
    $this->assertModelMissing($post);
});

test('non-owner cannot delete a post', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $post = Post::factory()->for($owner)->create();

    $response = $this->actingAs($other)->delete(route('posts.destroy', $post));

    $response->assertStatus(403);
    $this->assertModelExists($post);
});
