<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class CreateAdminsTable extends Migration
{
    protected $defaultAdmin = [
        'id' => 1,
        'name' => 'admin',
        'account' => 'admin',
        'password' => '123123123',
        'group_id' => '1',
        'parent_id' => '-1',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('account')->unique();
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->defaultAdmin['password'] = Hash::make($this->defaultAdmin['password']);
        DB::table('admins')->insert($this->defaultAdmin);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
