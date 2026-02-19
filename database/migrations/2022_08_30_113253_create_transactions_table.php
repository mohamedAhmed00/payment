<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('amount');
            $table->string('currency');
            $table->float('rate');
            $table->enum('action', ['pay', 'refund']);
            $table->json('services');
            $table->json('customer');
            $table->string('client_key', 100);
            $table->foreignId('payment_type_id')->constrained();
            $table->foreignId('payment_method_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
