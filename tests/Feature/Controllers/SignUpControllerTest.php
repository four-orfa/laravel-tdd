<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SignUpController
 */
class SignUpControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test SignUp Test
     */
    public function findSignUp()
    {
        $this->get('signup')->assertOk();
    }

    /**
     * @test user registration
     */
    public function userRegistrationTest()
    {
        // database insert
        $validData = [
            'name' => 'Tomas',
            'email' => 'aaa@bbb.net',
            'password' => 'abc12345'
        ];

        // signup, login, and redirect myPage.
        $this->post('signup', $validData)->assertRedirect('mypage/blogs');

        // Database check. Other than password.
        unset($validData['password']);
        $this->assertDatabaseHas('users', $validData);

        // password inspection
        $user = User::firstWhere($validData);

        $this->assertTrue(Hash::check('abc12345', $user->password));

        // login check
        $this->assertAuthenticatedAs($user);
    }

    /** @test invalid test data */
    public function userRegistrationInvalidDataTest()
    {
        $url = 'signup';

        // $this->get('signup');
        $this->from('signup')->post($url, [])
            ->assertRedirect($url);

        $this->post($url, ['name' => ''])->assertInvalid('name');
        $this->post($url, ['name' => str_repeat('a', 21)])->assertInvalid('name');
        $this->post($url, ['name' => str_repeat('a', 20)])->assertValid('name');

        $this->post($url, ['email' => ''])->assertInvalid('email');
        $this->post($url, ['email' => 'abcd@ああ.com'])->assertInvalid('email');
        User::factory()->create(['email' => 'example@gmail.com']);
        $this->post($url, ['email' => 'example@gmail.com'])->assertInvalid('email');

        $this->post($url, ['password' => ''])->assertInvalid('password');
        $this->post($url, ['password' => 'abc1234'])->assertInvalid('password');
        $this->post($url, ['password' => 'abc12345'])->assertValid('password');
    }

    /** @test error message */
    public function errorMessageTest()
    {
        $url = 'signup';

        $postData = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        $this->from($url)->post($url, $postData)->assertRedirect($url);

        // get message after redirect.
        $this->get($url)
            ->assertSeeText('nameは必ず指定してください。')
            ->assertSee('emailは必ず指定してください。')
            ->assertSee('passwordは必ず指定してください。');
    }
}
