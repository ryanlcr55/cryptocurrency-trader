<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrderRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_order_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->string('robot_uid')->comment('機器人流水號');
            $table->string('symbol', 16);
            $table->enum('action', ['buy', 'sell']);
            $table->string('exchange_order_id')->comment('訂單號');
            $table->unsignedDecimal('price', 28, 18)->nullable()->comment('價格');
            $table->unsignedDecimal('cost', 28, 18)->nullable()->comment('成本');
            $table->unsignedDecimal('quantity', 28, 18)->nullable()->comment('數量');
            $table->unsignedDecimal('fee', 28, 18)->nullable()->comment('手續費');
            $table->dateTime('order_created_at');
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
        Schema::dropIfExists('user_order_records');
    }
}
