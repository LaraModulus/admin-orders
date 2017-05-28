<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_items', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->string('product_name', 255);
            $table->integer('product_id')->unsigned()->index();
            $table->decimal('price', 6, 2);
            $table->decimal('qty', 6, 3)->unsigned();
            $table->integer('order_id')->unsigned()->index();
            $table->decimal('weight', 6, 3)->default(0);
            $table->text('selected_options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_items');
    }
}
