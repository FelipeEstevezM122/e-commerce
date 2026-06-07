<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// agrega la columna expires_at a la tabla de tokens de sanctum
// versiones nuevas de sanctum la necesitan para poder poner fecha de vencimiento a los tokens
return new class extends Migration
{
    public function up(){
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('abilities');
        });
    }

    public function down(){
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};