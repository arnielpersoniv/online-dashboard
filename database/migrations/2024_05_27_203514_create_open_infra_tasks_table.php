<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpenInfraTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_infra_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('lid_no');
            $table->string('category');
            $table->string('adhoc_category')->nullable();
            $table->string('task');
            $table->string('adhoc_task')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('agent_id');
            $table->dateTime('time_start');
            $table->dateTime('time_end')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('agent_id')
                    ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('open_infra_tasks');
    }
}
