<?php

namespace Tests\Feature\Controllers\Mypage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogMypageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** * @test auth login. */
    public function canOpenAuthenticated()
    {
        // not authenticated
        $this->get('mypage/blogs')
            ->assertRedirect('mypage/login');

        // authenticated
        $this->login();

        $this->get('mypage/blogs')->assertOk();
    }
}
