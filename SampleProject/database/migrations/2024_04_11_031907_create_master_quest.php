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
        Schema::create('master_quest', function (Blueprint $table) {
            $table->unsignedInteger('quest_id')->default(0);
			$table->string('quest_name')->charset('utf8');
			$table->string('quest_detail')->charset('utf8');
			$table->Integer('quest_star')->default(0);
            $table->timestamp('open_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('close_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->unsignedInteger('item_type')->default(0);
			$table->unsignedInteger('item_count')->default(0);
			$table->primary('quest_id');
        });
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