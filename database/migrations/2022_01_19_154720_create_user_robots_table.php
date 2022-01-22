<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRobotReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_robot_references', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnUpdate()
                ->restrictOnDelete();
            $table->unsignedBigInteger('signal_id')->index();
            $table->unique(['user_id', 'signal_id']);
            $table->string('base_coin_code', 16)->default('usdt');
            $table->enum('exchange', ['binance'])->default('binance');
            $table->unsignedInteger('unit_percent');
            $table->unsignedInteger('limit_percent');
            $table->unsignedInteger('stop_percent');
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
        Schema::dropIfExists('user_robot_references');
    }
}
