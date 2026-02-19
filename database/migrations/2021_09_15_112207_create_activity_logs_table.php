<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('subject')->nullable();
            $table->string('url');
            $table->string('route_name', 100)->nullable();
            $table->string('method', 30);
            $table->ipAddress('ip');
            $table->string('agent')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('organization_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
