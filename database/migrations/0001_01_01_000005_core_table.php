<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residential_sectors', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('identification_type');
            $table->string('identification');
            $table->string('name');
            $table->string('first_last_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->string('status');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('residential_sector_id')->nullable()->constrained('residential_sectors');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists("residential_sectors");
        Schema::dropIfExists("clients");
    }
};
