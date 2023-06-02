<?php

namespace Tests\Feature;

use App\Models\Boards;
use App\Models\User;
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
    public function test_index_guest_redirect()
    {
        $response = $this->get('/boards');

        $response->assertRedirect('/users/login');
    }

    public function test_index_user_auth_return_view() {
        // 테스트용 유저 생성. BoardsController 파일 보고 비교하여 입력
        $user = new User([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            ,'password' => 'asdasd'
        ]);
        $user->save();

        $board1 = new Boards([
            'title' => 'test1'
            ,'content' => 'content1'
        ]);
        $board1->save();

        $board2 = new Boards([
            'title' => 'test23'
            ,'content' => 'content2'
        ]);
        $board2->save();

        $response = $this->actingAs($user)->get('/boards');

        // $this->assertAuthenticatedAs($user);
        // $response->assertViewIs('list');
        $response->assertViewHas('data');
        $response->assertSee('test1');
        $response->assertSee('test23');
    }
}
