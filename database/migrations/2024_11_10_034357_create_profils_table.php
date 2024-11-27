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
        Schema::create('tb_profil', function (Blueprint $table) {
            $table->string('id_profil', 16)->primary();
            $table->string('id_role', 16);
            $table->string('username');
            $table->string('nama');
            $table->string('password');
            $table->timestamps();

            $table->foreign('id_role')
                ->references('id_role')->on('tb_role')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_profil', function (Blueprint $table) {
            $table->dropForeign(['id_role']);
        });

        Schema::dropIfExists('tb_profil');
    }
};
