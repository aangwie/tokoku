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
        // Alamat pengiriman pelanggan (bisa lebih dari 1)
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label', 50)->default('Rumah'); // Rumah, Kantor, dll
            $table->string('recipient_name', 100);
            $table->string('phone', 20);
            $table->text('full_address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Rekening bank pelanggan untuk pengembalian dana
        Schema::create('customer_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bank_name', 100);
            $table->string('account_number', 50);
            $table->string('account_holder', 100);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_bank_accounts');
        Schema::dropIfExists('customer_addresses');
    }
};
