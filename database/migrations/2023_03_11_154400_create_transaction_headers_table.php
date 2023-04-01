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
        Schema::create('transaction_headers', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no');
            $table->integer('transaction_type_id');
            $table->bigInteger('branch_id');
            $table->date('transaction_date');
            $table->string('received_by');
            $table->string('delivered_by');
            $table->string('remarks');
            $table->bigInteger('customer_id');
            $table->string('customer_category');
            $table->bigInteger('payment_id');
            $table->integer('status'); // status 0 - pending / editable, 1 - posted, 2 - approved
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
        Schema::dropIfExists('transaction_headers');
    }
};
