<?php

namespace Tests\Feature\Controllers\Mypage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Mypage\UserLoginController
 */
class UserLoginControllerTest extends TestCase
{
    /**
     * @test index
     */
    public function findLoginTest()
    {
        $this->get('mypage/login')->assertOk();
    }

    /**
     * login input validation
     *
     * @test login
     */
    public function loginValidation()
    {
        $url = 'mypage/login';
        $this->from($url)->post($url, [])->assertRedirect($url);

        $this->post($url, ['email' => ''])->assertInvalid('email');
        $this->post($url, ['email' => 'aaa@ああ.com'])->assertInvalid('email');

        $this->post($url, ['password' => ''])->assertInvalid('password');
    }
}
