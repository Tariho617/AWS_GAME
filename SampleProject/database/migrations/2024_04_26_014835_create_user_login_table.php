<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_login'))
        {
            Schema::create('user_login', function (Blueprint $table) {
                $table->string('user_id', 37)->charset('utf8')->primary();
                $table->unsignedSmallInteger('login_day')->default(0);
                $table->timestamp('last_login_at')->default('CURRENT_TIMESTAMP');
                $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
                $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login');
    }
}
