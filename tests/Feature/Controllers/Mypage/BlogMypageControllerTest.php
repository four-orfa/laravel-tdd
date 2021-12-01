<?php

namespace Tests\Feature\Controllers\Mypage;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogMypageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test guestNot */
    public function guestManagementTest()
    {
        $url = 'mypage/login';

        // not authenticated
        $this->get('mypage/blogs')->assertRedirect($url);
        $this->get('mypage/blogs/create')->assertRedirect($url);
        $this->post('mypage/blogs/create', [])->assertRedirect($url);
    }

    /** * @test auth login. */
    public function canOpenAuthenticated()
    {
        // authenticated
        $user = $this->login();

        $myBlog = Blog::factory()->create([
            'user_id' => $user,
        ]);
        $other = Blog::factory()->create();


        $this->get('mypage/blogs')->assertOk()
            ->assertSee($myBlog->title)
            ->assertDontSee($other->title);
    }

    /** @test myPage */
    function createMypageBlogTest()
    {
        $this->login();
        $this->get('mypage/blogs/create')->assertOk();
    }

    /** @test store */
    function createNewBlogTest()
    {
        $this->login();

        $validData = Blog::factory()->validData();

        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1'); // SQLite inMemory

        $this->assertDatabaseHas('blogs', $validData);
    }

    /** @test store */
    function createPrivateMypageBlogTest()
    {
        $this->login();

        $validData = Blog::factory()->validData();

        unset($validData['status']);

        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1'); // SQLite inMemory

        $validData['status'] = '0';

        $this->assertDatabaseHas('blogs', $validData);
        $data = Blog::get()->all();
    }
}
