<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterQuest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('master_quest'))
        {
            Schema::create('master_quest', function (Blueprint $table) {
                $table->unsignedInteger('quest_id')->default(0)->primary();
                $table->string('quest_name')->charset('utf8');
                $table->string('quest_detail')->charset('utf8');
                $table->Integer('quest_star')->default(0);
                $table->timestamp('open_at')->default('CURRENT_TIMESTAMP');
                $table->timestamp('close_at')->default('CURRENT_TIMESTAMP');
                $table->unsignedInteger('item_type')->default(0);
                $table->unsignedInteger('item_count')->default(0);
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
        Schema::dropIfExists('master_quest');
    }
}
