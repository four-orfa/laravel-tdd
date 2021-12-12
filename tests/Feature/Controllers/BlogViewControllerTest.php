<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\BlogShowLimit;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
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

    /** @test index page. */
    public function blogListOpenedAndClosed()
    {
        Blog::factory()->create(
            [
                // dont use blogFactory function.
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
            ->assertSee('Blog');
    }

    /** @test blog detail page.
     *  show comment, order by created_at desc.
     */
    public function blogDetailTest()
    {
        $this->withoutMiddleware([BlogShowLimit::class]);

        $blog = Blog::factory()->create();

        // if use hard cording.
        // Comment::factory()->create([
        //     'created_at' => now()->sub('5 days'),
        //     'name' => 'Jon',
        //     'blog_id' => $blog->id
        // ]);
        // Comment::factory()->create([
        //     'created_at' => now()->sub('3 days'),
        //     'name' => 'Mike',
        //     'blog_id' => $blog->id
        // ]);
        // Comment::factory()->create([
        //     'created_at' => now()->sub('1 days'),
        //     'name' => 'Steven',
        //     'blog_id' => $blog->id
        // ]);

        $blog = Blog::factory()->withCommentsData([
            ['created_at' => now()->sub('5 days'), 'name' => 'Jon',],
            ['created_at' => now()->sub('3 days'), 'name' => 'Mike',],
            ['created_at' => now()->sub('1 days'), 'name' => 'Steven',]
        ])->create();

        $this->get('detail/' . $blog->id)
            ->assertOk()
            ->assertSee($blog->title)
            ->assertSee($blog->user->name)
            ->assertSeeInOrder(['Jon', 'Mike', 'Steven']);
    }

    /** @test blog detail closed.*/
    public function blogDetailClosedTest()
    {
        // use blogFactory function.
        $blog = Blog::factory()->closed()->create();

        $this->get('detail/' . $blog->id)
            ->assertForbidden();
    }

    /** @test 1/1 call Happy NewYear! */
    public function newYearCommentTest()
    {
        $this->withoutMiddleware([BlogShowLimit::class]);

        $blog = Blog::factory()->create();

        Carbon::setTestNow('2020-12-31');
        $this->get('detail/' . $blog->id)
            ->assertOk()
            ->assertDontSee('Happy NewYear!');

        Carbon::setTestNow('2021-01-01');
        $this->get('detail/' . $blog->id)
            ->assertOk()
            ->assertSee('Happy NewYear!');
    }
}
