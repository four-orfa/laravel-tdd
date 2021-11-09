<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
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

        // 実行
        $response = $this->get('/');

        // 検証
        $response->assertStatus(200);
    }
}
