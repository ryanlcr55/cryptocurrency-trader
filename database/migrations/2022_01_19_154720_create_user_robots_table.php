<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRobotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_robots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnUpdate()
                ->restrictOnDelete();
            $table->unsignedBigInteger('signal_id')->index();
            $table->unique(['user_id', 'signal_id']);
            $table->unsignedInteger('amount');
            $table->unsignedInteger('limit');
            $table->unsignedInteger('stop');
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
        Schema::dropIfExists('user_robots');
    }
}
