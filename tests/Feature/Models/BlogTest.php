<?php

namespace Tests\Feature\Models;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test user */
    public function userRelationTest()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(User::class, $blog->user);
    }

    /** @test comments */
    public function commentsRelationTest()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(Collection::class, $blog->comments);
    }
}
