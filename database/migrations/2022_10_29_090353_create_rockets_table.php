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
        Schema::create('rockets', function (Blueprint $table) {
            $table->id();
            $table->enum('rocket_type', ['a', 'b', 'c']);
            $table->dateTime('launch_time');
            $table->dateTime('estimate_return_time');
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
        Schema::dropIfExists('rockets');
    }
};