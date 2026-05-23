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
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('province_code', 10)->nullable()->after('phone');
            $table->string('city_code', 10)->nullable()->after('province_code');
            $table->string('district_code', 10)->nullable()->after('city_code');
            $table->string('village_code', 10)->nullable()->after('district_code');
            $table->text('street_address')->nullable()->after('village_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn(['province_code', 'city_code', 'district_code', 'village_code', 'street_address']);
        });
    }
};
