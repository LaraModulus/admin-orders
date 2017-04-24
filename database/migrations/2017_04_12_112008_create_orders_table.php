<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('names', 255);
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->text('admin_note')->nullable();
            $table->boolean('seen')->index()->default(0);
            $table->enum('status', ['new', 'in_progress', 'finished', 'canceled'])->default('new')->index();
            $table->enum('payment_method', ['on_delivery', 'bank', 'card'])->index();
            $table->text('invoice_data')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
