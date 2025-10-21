<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
        });
    }
public function down()
{
    Schema::dropIfExists('promotions');
}


};
