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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->name('name');
            $table->float('address_latitude');
            $table->float('address_longitude');

            $table->unsignedBigInteger('education_status_id');
            $table->unsignedBigInteger('campus_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('district_id');
            $table->foreign('education_status_id')->references('id')->on('education_statuses');
            $table->foreign('campus_id')->references('id')->on('campuses');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
