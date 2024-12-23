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
        Schema::create('tb_data_akun', function (Blueprint $table) {
            $table->string('id_data_akun', 16)->primary();
            $table->string('id_sub_akun', 16);
            $table->integer('kode');
            $table->string('nama');
            $table->integer('debit')->default(0)->nullable();
            $table->integer('kredit')->default(0)->nullable();
            $table->string('id_profil', 16);
            $table->timestamps();

            $table->foreign('id_sub_akun')
                ->references('id_sub_akun')->on('tb_sub_akun')
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
        Schema::table('tb_data_akun', function (Blueprint $table) {
            $table->dropForeign(['id_sub_akun']);
            $table->dropForeign(['id_profil']);
        });

        Schema::dropIfExists('tb_data_akun');
    }
};
