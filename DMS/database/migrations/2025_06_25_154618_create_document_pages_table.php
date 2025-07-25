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
        Schema::create('document_pages', function (Blueprint $table) {
            $table->id();
             $table->foreignId('document_id')->constrained('documents')->onDelete('cascade'); 
            $table->string('original_name'); // original file name
            $table->string('file_path');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_pages');
    }
};
