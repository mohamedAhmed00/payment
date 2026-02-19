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
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->bigInteger('transaction_id');
            $table->json('request')->nullable()->change();
            $table->json('response')->nullable()->change();
            $table->bigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
            $table->json('request')->change();
            $table->json('response')->change();
            $table->bigInteger('user_id')->change();
        });
    }
};
