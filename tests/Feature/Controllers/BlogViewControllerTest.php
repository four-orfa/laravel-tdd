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
        $blog1 = Blog::factory()->create();
        $blog2 = Blog::factory()->create();
        $blog3 = Blog::factory()->create();

        // variable pattern.
        $this->get('/')
            ->assertOk()
            ->assertViewIs('index')
            ->assertSee($blog1->title)
            ->assertSee($blog2->title)
            ->assertSee($blog3->title)
            ->assertSee($blog1->user->name)
            ->assertSee($blog2->user->name)
            ->assertSee($blog3->user->name);


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

    /** @test */
    function factoryObservation()
    {
        $blog = Blog::factory()->create();
    }
}
