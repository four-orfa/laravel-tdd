<?php

namespace Tests\Feature\Controllers\Mypage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Mypage\UserLoginController
 */
class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test login */
    public function canLogin()
    {
        $postData = [
            'email' => 'aaa@gmail.com',
            'password' => 'abc12345',
        ];

        $dbData = [
            'email' => 'aaa@gmail.com',
            'password' => bcrypt($postData['password'])
        ];

        $user = User::factory()->create($dbData);

        $this->post('mypage/login', $postData)->assertRedirect('mypage/blogs');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function canNotLogin()
    {
        $url = 'mypage/login';

        $postData = [
            'email' => 'aaa@gmail.com',
            'password' => 'aaa12345',
        ];

        $dbData = [
            'email' => 'aaa@gmail.com',
            'password' => bcrypt('abc12345')
        ];

        User::factory()->create($dbData);

        $this->from($url)->post($url, $postData)
            ->assertRedirect($url);

        // if invalid email or password, redirect login and find error message.
        $this->from($url)->followingRedirects()->post($url, $postData)
            ->assertSee('Invalid email or password.');
    }
}
