<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
//    protected $defaultUser = [
//        'id' => 100000,
//        'name' => 'user1',
//        'email' => 'user1@bonbon.land',
//        'email_verification_status' => 1,
//        'phone' => '86-18888888888',
//        'phone_verification_status' => 1,
//        'password' => '$2y$10$izGTGZoxk20TukgyTA0X..bo3/uSjHT3Yq5VVBm6HSUkVhdf8.qHq',   //123123123
//    ];

    protected $defaultUser = [
        'eth_address' => '0xDD0680dB212610909DAbEcf4231a30c2fF7437B4',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('users', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->string('name')->nullable()->unique();
//            $table->string('email')->nullable()->unique();
//            $table->unsignedTinyInteger('email_verification_status')->default(0)->comment('邮件验证状态(0-未验证,1-已验证)');
//            $table->string('phone')->nullable()->unique();
//            $table->unsignedTinyInteger('phone_verification_status')->default(0)->comment('手机验证状态(0-未验证,1-已验证)');
//            $table->string('password');
//            $table->unsignedTinyInteger('active')->default(1)->comment('启用状态(0-禁用,1-正常)');
//            $table->rememberToken();
//            $table->string('register_ip')->default('0.0.0.0')->comment('注册时客户端ip');
//            $table->string('last_login_ip')->nullable()->comment('上次登录ip');
//            $table->timestamp('last_login_time')->nullable()->comment('上次登录时间');
//            $table->timestamps();
//        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('eth_address', 42)->unique()->comment('以太地址');
            $table->string('name')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->unsignedTinyInteger('active')->default(1)->comment('启用状态(0-禁用,1-正常)');
            $table->string('register_ip')->default('0.0.0.0')->comment('注册时客户端ip');
            $table->string('last_login_ip')->nullable()->comment('上次登录ip');
            $table->timestamp('last_login_time')->nullable()->comment('上次登录时间');
            $table->timestamps();
        });

        //id从100000开始
        DB::update("ALTER TABLE users AUTO_INCREMENT = 100000;");

        DB::table('users')->insert($this->defaultUser);
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
