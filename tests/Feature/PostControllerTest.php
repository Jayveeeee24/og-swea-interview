<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index function of PostController.
     *
     * @return void
     */
    public function testIndex()
    {

        $user = User::factory()->create();

        $post1 = Post::factory()->create([
            'user_id' => $user->id,
            'body' => 'Sample post content 1',
        ]);
        $post2 = Post::factory()->create([
            'user_id' => $user->id,
            'body' => 'Sample post content 2',
        ]);

        $response = $this->get('/posts');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'user_id',
                'body',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $response->assertJson([
            [
                'id' => $post2->id,
                'user_id' => $post2->user_id,
                'body' => $post2->body,
                'user' => [
                    'id' => $post2->user->id,
                    'name' => $post2->user->name,
                    'email' => $post2->user->email,
                    'created_at' => $post2->user->created_at->toISOString(),
                    'updated_at' => $post2->user->updated_at->toISOString(),
                ],
            ],
            [
                'id' => $post1->id,
                'user_id' => $post1->user_id,
                'body' => $post1->body,
                'user' => [
                    'id' => $post1->user->id,
                    'name' => $post1->user->name,
                    'email' => $post1->user->email,
                    'created_at' => $post1->user->created_at->toISOString(),
                    'updated_at' => $post1->user->updated_at->toISOString(),
                ],
            ],
            
        ]);

        $sortPostDesc = Post::orderByDesc('id')->get();
        $response->assertJson($sortPostDesc->toArray());
    }
}
