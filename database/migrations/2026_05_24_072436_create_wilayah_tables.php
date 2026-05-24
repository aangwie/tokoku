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
        // Tabel Provinsi
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode provinsi dari API
            $table->string('name'); // Nama provinsi
            $table->timestamps();
        });

        // Tabel Kota/Kabupaten
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->string('code')->unique(); // Kode kota dari API
            $table->string('name'); // Nama kota
            $table->timestamps();
            
            $table->index('province_id');
        });

        // Tabel Kecamatan
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->string('code')->unique(); // Kode kecamatan dari API
            $table->string('name'); // Nama kecamatan
            $table->timestamps();
            
            $table->index('city_id');
        });

        // Tabel Kelurahan/Desa
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->string('code')->unique(); // Kode kelurahan dari API
            $table->string('name'); // Nama kelurahan
            $table->timestamps();
            
            $table->index('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
