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
            'password' => 'abcd12345'
        ];

        $this->post('signup', $validData)->assertOk();

        unset($validData['password']);
        $this->assertDatabaseHas('users', $validData);

        // password inspection
        $user = User::firstWhere($validData);
        $this->assertNotNull($validData);

        $this->assertTrue(Hash::check('abcd12345', $user->password));

        // redirect myPage

    }
}
