<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no');
            $table->bigInteger('transaction_header_id');
            $table->bigInteger('item_id');
            $table->string('qr_code');
            $table->double('quantity');
            $table->double('selling_price')->default(0);
            $table->double('amount');
            $table->string('sale_type');
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('transaction_details');
        //
    }
};
