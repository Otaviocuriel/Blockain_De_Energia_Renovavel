<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cidade')) {
                $table->string('cidade')->nullable()->after('endereco');
            }
            if (!Schema::hasColumn('users', 'tipo_energia')) {
                $table->string('tipo_energia')->nullable()->after('cidade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'tipo_energia')) {
                $table->dropColumn('tipo_energia');
            }
            if (Schema::hasColumn('users', 'cidade')) {
                $table->dropColumn('cidade');
            }
        });
    }
};