<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test index
     *
     * @return void
     */
    public function blogTopPageTest()
    {
        $blog1 = Blog::factory()->hasComments(1)->create();
        $blog2 = Blog::factory()->hasComments(2)->create();
        $blog3 = Blog::factory()->hasComments(3)->create();

        // variable pattern.
        $this->get('/')
            ->assertOk()
            ->assertViewIs('index')
            ->assertSee($blog1->title)
            ->assertSee($blog2->title)
            ->assertSee($blog3->title)
            ->assertSee($blog1->user->name)
            ->assertSee($blog2->user->name)
            ->assertSee($blog3->user->name)
            ->assertSee('(1件のコメント)')
            ->assertSee('(2件のコメント)')
            ->assertSee('(3件のコメント)')
            ->assertSeeInOrder([$blog3->title, $blog2->title, $blog1->title]);


        // constant pattern.
        Blog::factory()->create(['title' => 'abc']);
        Blog::factory()->create(['title' => 'def']);
        Blog::factory()->create(['title' => 'ghi']);

        $this->get('/')
            ->assertOk()
            ->assertViewIs('index')
            ->assertSee('abc')
            ->assertSee('def')
            ->assertSee('ghi');
    }

    /** @test index */
    public function BlogListOpenedAndClosed()
    {
        Blog::factory()->create(
            [
                'status' => Blog::CLOSED,
                'title' => 'BlogA',
            ]
        );
        Blog::factory()->create(['title' => 'BlogB']);
        Blog::factory()->create(['title' => 'BlogC']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('BlogA')
            ->assertSee('BlogB')
            ->assertSee('BlogC');
    }
}
