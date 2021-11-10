<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public $data;
    protected function setUp(): void
    {
        parent::setUp();

        $this->data = 'xxx';
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        // 準備
        $user = User::factory()->create();


        // 実行
        $response = $this->get('/');

        // 検証
        $response->assertStatus(200);
    }
}
