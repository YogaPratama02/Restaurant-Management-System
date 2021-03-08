<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('table_id');
            $table->string('table_name');
            $table->bigInteger('user_id')->unsigned();
            $table->string('user_name');
            $table->string('customer_name')->default("");
            $table->string('customer_phone')->default("");
            $table->decimal('total_hpp')->default(0);
            $table->decimal('total_price')->default(0);
            $table->decimal('total_vat')->default(0);
            $table->decimal('total_vatprice')->default(0);
            $table->decimal('total_received')->default(0);
            $table->decimal('change')->default(0);
            $table->string('payment_type')->default("");
            $table->string('sale_status')->default("unpaid");
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sales');
    }
}
