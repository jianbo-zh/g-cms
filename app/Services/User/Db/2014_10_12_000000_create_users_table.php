<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_type', 20)->comment('用户类型');
            $table->unsignedInteger('app_id')->nullable()->comment('应用编号');
            $table->string('username')->unique()->comment('账户');
            $table->string('nickname')->comment('昵称');
            $table->string('avatar')->comment('头像');
            $table->string('phone')->comment('电话');
            $table->string('email')->comment('邮箱');
            $table->string('password')->comment('密码');
            $table->string('state')->comment('状态');
            $table->string('api_token')->nullable()->comment('接口访问TOKEN');
            $table->rememberToken()->comment('记住我TOKEN');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `users` COMMENT '用户表'");

        // 初始化超管
        DB::table('users')->insert([
            'user_type'         => 'platform',
            'app_id'            => null,
            'username'          => 'admin',
            'nickname'          => 'admin',
            'avatar'            => '',
            'phone'             => '18215652865',
            'email'             => 'tipine@163.com',
            'state'             => 1,
            'password'          => \Illuminate\Support\Facades\Hash::make('admin'),
            'api_token'         => '123456'
        ]);
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
}
