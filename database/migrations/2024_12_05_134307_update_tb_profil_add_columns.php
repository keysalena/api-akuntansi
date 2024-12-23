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
        Schema::table('tb_profil', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('password'); 
            $table->string('email')->unique()->nullable()->after('alamat'); 
            $table->string('logo')->nullable()->after('email'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_profil', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'email', 'logo']);
        });
    }
};
