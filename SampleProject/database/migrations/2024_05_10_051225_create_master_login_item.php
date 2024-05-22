<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterLoginItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('master_login_item'))
        {
            Schema::create('master_login_item', function (Blueprint $table)
            {
                $table->unsignedSmallInteger('login_day')->default(0)->primary();
                $table->unsignedSmallInteger('item_type')->default(0);
                $table->unsignedSmallInteger('item_count')->default(0);
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
        Schema::dropIfExists('master_login_item');
    }
}
