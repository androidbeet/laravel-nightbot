<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNightbot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nightbot_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('state')->nullable();
            $table->string('access_token')->nullable();
            $table->string('expires_in')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('token_type')->nullable();
            $table->string('scope')->nullable();
            $table->timestamps();
        });

//        Schema::create('nightbot_users', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('token_id');
//            $table->string('nb_user_id')->nullable();
//            $table->string('display_name')->nullable();
//            $table->string('name')->nullable();
//            $table->string('provider')->nullable();
//            $table->string('provider_id')->nullable();
//            $table->string('admin')->nullable();
//            $table->string('avatar')->nullable();
//            $table->timestamps();
//        });

        Schema::create('nightbot_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->nullable();
            $table->string('client')->nullable();
            $table->string('secret')->nullable();
            $table->string('redirect')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nightbot_tokens');
        // Schema::dropIfExists('nightbot_users');
        Schema::dropIfExists('nightbot_apps');
    }
}
