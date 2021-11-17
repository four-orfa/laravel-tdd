<?php

namespace Tests\Feature\Models;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use GuzzleHttp\Promise\Create;
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

    /** @test status->open */
    public function scopeOpenedTest()
    {
        $blogA = Blog::factory()->create([
            'status' => Blog::CLOSED,
            'title' => 'BlogA'
        ]);
        $blogB = Blog::factory()->create(['title' => 'BlogB']);
        $blogC = Blog::factory()->create(['title' => 'BlogC']);

        $blogs = Blog::open()->get();

        $this->assertFalse($blogs->contains($blogA));
        $this->assertTrue($blogs->contains($blogB));
        $this->assertTrue($blogs->contains($blogC));
    }

    /** @test if Blog is public, return false, and closed return true. */
    public function isClosedTest()
    {
        $blogPublic = Blog::factory()->make();
        $this->assertFalse($blogPublic->isClosed());

        $blogClosed = Blog::factory()->closed()->make();
        $this->assertTrue($blogClosed->isClosed());
    }
}
