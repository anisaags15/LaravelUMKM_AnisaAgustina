<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk menambahkan kolom.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // 1. Kolom untuk menyimpan file bukti transfer
            $table->string('payment_proof')->nullable()->after('notes');
            // 2. Kolom untuk menyimpan status konfirmasi admin
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])
                  ->default('pending')
                  ->after('payment_proof');
            // 3. Kolom untuk menyimpan ID admin yang mengkonfirmasi (foreign key)
            $table->unsignedBigInteger('confirmed_by')->nullable()->after('payment_status');
            // 4. Kolom untuk menyimpan waktu konfirmasi
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');
        });
    }

    /**
     * Membalikkan migration (menghapus kolom).
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'payment_status', 'confirmed_by', 'confirmed_at']);
        });
    }
};