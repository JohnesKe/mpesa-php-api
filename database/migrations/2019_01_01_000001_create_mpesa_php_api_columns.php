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
    public function up(): void
    {
        Schema::create('mpesa_php_api', function (Blueprint $table) {
            $table->id();
            $table->string('response_code');
            $table->string('response_description');
            $table->string('merchant_request_id');
            $table->string('checkout_request_id')->unique();
            $table->string('customer_message');
            $table->string('result_code')->nullable();
            $table->string('result_description')->nullable();
            $table->string('amount')->nullable();
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('mpesa_phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_php_api');
    }
};
