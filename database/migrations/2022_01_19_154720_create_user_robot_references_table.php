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
            $table->string('name')->nullable();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnUpdate()
                ->restrictOnDelete();
            $table->unsignedBigInteger('signal_id')->index();
            $table->unique(['user_id', 'signal_id']);
            $table->string('base_coin_code', 16)->default('usdt')->comment('成本幣別');
            $table->unsignedInteger('unit_percent')->comment('每次購買成本百分比');
            $table->unsignedInteger('limit_percent')->comment('止營百分比');
            $table->unsignedInteger('stop_percent')->comment('止損百分比');
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
