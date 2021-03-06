<?php

namespace Tests\Feature\Controllers\Mypage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;

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

    /** @test login (if use validation test.) */
    public function validationExceptionTest()
    {
        // necessary exception test.
        $this->withoutExceptionHandling();

        $postData = [
            'email' => 'aaa@gmail.com',
            'password' => 'abc12345',
        ];

        // exception test only.
        // $this->expectException(ValidationException::class);
        // $this->post('mypage/login', $postData);

        // exception with message test.
        try {
            $this->post('mypage/login', $postData);
            $this->fail('not validationException');
        } catch (ValidationException $e) {
            $this->assertEquals('Invalid email or password.', $e->errors()['email'][0] ?? '');
        }
    }

    /** @test logout */
    public function logoutTest()
    {
        $this->login();

        $url = 'mypage/login';

        $this->post('mypage/logout')
            ->assertRedirect($url);

        $this->get($url)
            ->assertSee('Logged out');

        $this->assertGuest();
    }
}
