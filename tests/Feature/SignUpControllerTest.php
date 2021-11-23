<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        // validation

        // database insert
        $validData = [
            'name' => 'Tomas',
            'email' => 'aaa@bbb.net',
            'password' => 'abc12345'
        ];

        $this->post('signup', $validData)->assertOk();

        unset($validData['password']);
        $this->assertDatabaseHas('users', $validData);

        // password inspection
        $user = User::firstWhere($validData);
        $this->assertNotNull($validData);

        $this->assertTrue(Hash::check('abc12345', $user->password));

        // redirect myPage

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
}
