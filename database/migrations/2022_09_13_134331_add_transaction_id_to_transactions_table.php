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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('transaction_id');
            $table->foreignId('organization_id')->constrained();
            $table->unique(['client_key', 'organization_id', 'user_id', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('organization_id');
            $table->dropColumn('transaction_id');
            $table->dropUnique(['client_key', 'organization_id', 'user_id', 'transaction_id']);
        });
    }
};
