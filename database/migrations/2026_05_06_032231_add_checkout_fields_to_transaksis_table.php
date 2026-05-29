<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('nama_pemesan')->nullable()->after('user_id');
            $table->text('alamat')->nullable()->after('nama_pemesan');
            $table->string('no_hp', 15)->nullable()->after('alamat');
            $table->string('payment_method')->nullable()->after('no_hp');
            $table->string('shipping_method')->nullable()->after('payment_method');
            $table->string('status')->default('pending')->after('shipping_method');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pemesan', 'alamat', 'no_hp',
                'payment_method', 'shipping_method', 'status'
            ]);
        });
    }
};