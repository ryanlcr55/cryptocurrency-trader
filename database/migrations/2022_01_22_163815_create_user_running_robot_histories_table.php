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
            $table->unsignedInteger('signal_id')->index();
            $table->string('robot_uid')->unique()->comment('機器人流水號');
            $table->string('coin_code', 16)->comment('購買幣別');
            $table->string('base_coin_code', 16)->comment('成本幣別');
            $table->unsignedDecimal('base_cost', 28, 18)->comment('購買成本');
            $table->unsignedDecimal('quantity', 28, 18)->comment('購買數量');
            $table->unsignedDecimal('starting_price', 28, 18)->comment('起始價格');
            $table->unsignedDecimal('ending_price', 28, 18)->comment('結束價格');
            $table->unsignedDecimal('fee', 28, 18)->comment('手續費');
            $table->decimal('profit', 28, 18)->comment('利潤');
            $table->dateTime('creating_at');
            $table->dateTime('ending_at');
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
