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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('edisi');                  // contoh: "01"
            $table->unsignedSmallInteger('tahun');    // contoh: 2026
            $table->string('sampul');                 // path gambar sampul
            $table->string('file_pdf')->nullable();
            $table->timestamps();
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
