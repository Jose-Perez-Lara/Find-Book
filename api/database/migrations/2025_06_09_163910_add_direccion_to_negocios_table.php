<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->string('direccion')->nullable()->after('imagen_portada');
            $table->decimal('latitud', 10, 7)->nullable()->after('direccion');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
        });
    }

    public function down(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'latitud', 'longitud']);
        });
    }

};
