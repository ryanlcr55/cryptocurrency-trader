<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRunningRobotHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_running_robot_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('user_robot_reference_id')->index();  
            $table->string('robot_uid')->unique();
            $table->string('exchange', 16);
            $table->string('base_coin_code', 16);
            $table->string('target_coin_code', 16);
            $table->enum('type', ['long', 'sort']);
            $table->unsignedDecimal('amount', 28, 18);
            $table->unsignedDecimal('starting_price', 28, 18);
            $table->unsignedDecimal('ending_price', 28, 18);
            $table->determines('profit', 28, 18);
            $table->timestamps('creating_at');
            $table->timestamps('ending_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_running_robot_histories');
    }
}
