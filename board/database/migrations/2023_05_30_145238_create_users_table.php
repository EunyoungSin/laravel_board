<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // 이메일은 중복허용x. PK는 보통 숫자열을 줌. 문자열을 PK로 주면 검색속도가 느려짐.
            $table->string('name');
            $table->string('password'); // 라라벨 비밀번호 암호화 최소 필요자릿수 50개
            $table->timestamp('email_verified_at')->nullable(); // 이메일 인증 여부
            $table->rememberToken(); // 로그인 유지 기능 (단, 엘로퀀트 이용시만)
            $table->timestamps(); // created_at, updated_at 자동생성
            $table->softDeletes(); // deleted_at 자동생성
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
