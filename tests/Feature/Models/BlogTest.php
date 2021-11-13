<?php

namespace Tests\Feature\Models;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test user */
    public function userRelation()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(User::class, $blog->user);
    }
}
