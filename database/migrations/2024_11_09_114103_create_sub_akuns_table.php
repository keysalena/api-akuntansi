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
        Schema::create('tb_sub_akun', function (Blueprint $table) {
            $table->string('id_sub_akun', 16)->primary();
            $table->string('id_akun', 16);
            $table->integer('kode');
            $table->string('nama');
            $table->timestamps();

            $table->foreign('id_akun')
                ->references('id_akun')->on('tb_akun')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_sub_akun', function (Blueprint $table) {
            $table->dropForeign(['id_akun']);
        });

        Schema::dropIfExists('tb_sub_akun');
    }
};
