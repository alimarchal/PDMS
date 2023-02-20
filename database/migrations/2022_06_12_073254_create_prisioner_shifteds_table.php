<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prisioner_shifteds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prisoner_id');
            $table->foreign('prisoner_id')->references('id')->on('prisoners');
            $table->string('detention_authority')->nullable();
            $table->string('detention_place')->nullable();
            $table->string('detention_city')->nullable();
            $table->string('shifting_date_hijri', 10)->nullable();
            $table->text('other_details')->nullable();
            $table->date('shifting_date_gregorian')->nullable();
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
        Schema::dropIfExists('prisioner_shifteds');
    }
};
