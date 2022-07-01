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
        $this->get('mypage/blogs/edit/1')->assertRedirect($url);
        $this->post('mypage/blogs/edit/1')->assertRedirect($url);
        $this->delete('mypage/blogs/delete/1')->assertRedirect($url);
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
    }

    /** @test */
    function validationTest()
    {
        $url = 'mypage/blogs/create';
        $this->login();

        $this->from($url)->post($url, [])
            ->assertRedirect($url);

        app()->setlocale('testing');

        $this->post($url, ['title' => ''])->assertInvalid('title');
        $this->post($url, ['title' => str_repeat('a', 256)])->assertInvalid('title');
        $this->post($url, ['title' => str_repeat('a', 255), 'body' => 'test'])->assertValid('title');

        $this->post($url, ['body' => ''])->assertInvalid('body');
    }

    /** @test edit */
    function editOnlyMypage()
    {
        $blog = Blog::factory()->create();

        $this->login();

        $this->get('mypage/blogs/edit/' . $blog->id)->assertForbidden();
    }

    /** @test edit */
    function editMypageTest()
    {
        $blog = Blog::factory()->create();

        $this->login($blog->user);

        $this->get('mypage/blogs/edit/' . $blog->id)->assertOk();
    }

    /** @test update */
    function updateMyBlogTest()
    {
        $validData = [
            'title' => 'new title',
            'body' => 'new body',
            'status' => '1',
        ];

        $blog = Blog::factory()->create();

        $url = 'mypage/blogs/edit/' . $blog->id;

        $this->login($blog->user);

        $this->from($url)->post($url, $validData)
            ->assertRedirect($url);

        $this->get($url)
            ->assertSeeText('Blog Update');

        $this->assertDatabaseHas('blogs', $validData);

        // if blog necessary database count
        $this->assertCount(1, Blog::all());
        $this->assertEquals(1, Blog::count());

        // if new data test
        $this->assertEquals('new title', $blog->fresh()->title);
        $this->assertEquals('new body', $blog->fresh()->body);
        // another pattern
        $blog->refresh();
        $this->assertEquals('new title', $blog->title);
    }

    /** @test destroy */
    function myblogDeleteTest()
    {
        $blog = Blog::factory()->create();

        $this->login($blog->user);

        $this->delete('mypage/blogs/delete/' . $blog->id)
            ->assertRedirect('mypage/blogs');

        // DB delete test
        $this->assertCount(0, Blog::all());
    }

    /** @test cant destroy by guest */
    function guestDeleteTest()
    {
        $blog = Blog::factory()->create();

        $this->login();

        $this->delete('mypage/blogs/delete/' . $blog->id)
            ->assertForbidden();

        $this->assertCount(1, Blog::all());
    }
}
