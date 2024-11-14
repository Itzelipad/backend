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
        Schema::create('casos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->integer('edad');
            $table->string('telefono', 10);
            $table->char('genero');
            $table->time('hora');
            $table->date('fecha');
            $table->string('desglose');
            $table->decimal('pago', $precision = 12, $scale = 2);
            $table->unsignedBigInteger('id_doctor');
            $table->foreign('id_doctor')->references('id')->on('doctors');
            $table->unsignedBigInteger('id_reception');
            $table->foreign('id_reception')->references('id')->on('receptions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casos');
    }
};
