<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNightbotTokensTable extends Migration
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nightbot_tokens');
    }
}
