<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardsTest extends TestCase
{
    // php artisan make:test BoardsTest
    // 이름의 끝이 Test로 끝날 것

    use RefreshDatabase; // 테스트 완료 후 DB 초기화를 위한 트레이트(클래스 안에서 사용하는 객체)
    use DatabaseMigrations; // DB 마이그레이션을 위한 트레이트

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
