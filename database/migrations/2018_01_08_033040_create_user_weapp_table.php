<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWeappTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_weapp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->default(0)->comment('用户ID');
            $table->string('unionid', 30)->nullable()->default('')->comment('用户unionid');
            $table->string('openid', 30)->default('')->comment('用户openid');
            $table->string('session_key', 50)->default('')->comment('微信session_key');
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('headimgurl', 255)->nullable()->default('')->comment('用户微信头像');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_weapp');
    }
}
