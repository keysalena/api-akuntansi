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
        Schema::create('tb_jurnal', function (Blueprint $table) {
            $table->string('id_jurnal', 16)->primary();
            $table->string('id_tipe_jurnal', 16);
            $table->date('tanggal');
            $table->string('nama_transaksi');
            $table->integer('nominal');
            $table->string('id_debit', 16);
            $table->string('id_kredit', 16);
            $table->string('id_profil', 16)->nullable();
            $table->timestamps();

            $table->foreign('id_tipe_jurnal')
                ->references('id_tipe_jurnal')->on('tb_tipe_jurnal')
                ->onDelete('cascade');
            $table->foreign('id_debit')
                ->references('id_data_akun')->on('tb_data_akun')
                ->onDelete('cascade');
            $table->foreign('id_kredit')
                ->references('id_data_akun')->on('tb_data_akun')
                ->onDelete('cascade');
            $table->foreign('id_profil')
                ->references('id_profil')->on('tb_profil')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_jurnal', function (Blueprint $table) {
            $table->dropForeign(['id_tipe_jurnal']);
            $table->dropForeign(['id_debit']);
            $table->dropForeign(['id_kredit']);
            $table->dropForeign(['id_profil']);
        });

        Schema::dropIfExists('tb_jurnal');
    }
};
