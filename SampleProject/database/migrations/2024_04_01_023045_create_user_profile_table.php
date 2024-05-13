<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_profile'))
        {
            Schema::create('user_profile', function (Blueprint $table) {
                $table->string('user_id', 37)->charset('utf8')->primary();
                $table->string('user_name', 32)->charset('utf8');
                $table->unsignedInteger('jewel')->default(0);
                $table->unsignedInteger('jewel_free')->default(0);
                $table->unsignedInteger('friend_coin')->default(0);
                $table->unsignedSmallInteger('tutorial_progress')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('user_profile');
    }
}
