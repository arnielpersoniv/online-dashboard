<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->string('account_no');
            $table->string('status');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('released_by');
            $table->dateTime('time_start');
            $table->dateTime('time_end')->nullable();
            $table->dateTime('time_hold')->nullable();
            $table->dateTime('time_resume')->nullable();
            $table->string('hold_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')
                    ->references('id')->on('categories');
            $table->foreign('task_id')
                    ->references('id')->on('tasks');
            $table->foreign('released_by')
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
        Schema::dropIfExists('activities');
    }
}
