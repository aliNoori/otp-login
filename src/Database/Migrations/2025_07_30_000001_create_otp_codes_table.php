<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create OTP Codes Table
 *
 * This migration creates the `otp_codes` table used to store
 * one-time passwords (OTP) for phone-based authentication.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Creates the `otp_codes` table with fields for phone number,
     * OTP code, expiration timestamp, and standard timestamps.
     */
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();

            // Phone number associated with the OTP
            $table->string('phone')->index();

            // The actual OTP code
            $table->string('code');

            // Expiration timestamp for the OTP
            $table->timestamp('expires_at');

            // Laravel's created_at and updated_at timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the `otp_codes` table.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
