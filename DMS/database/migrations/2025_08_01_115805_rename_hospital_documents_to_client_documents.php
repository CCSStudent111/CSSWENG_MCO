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
        Schema::rename('hospital_documents', 'client_documents');

        Schema::table('client_documents', function (Blueprint $table) {
            
            $table->dropForeign('hospital_documents_hospital_id_foreign');
            $table->dropPrimary();
            $table->dropColumn('hospital_id');

            $table->foreignId('client_id')
                  ->constrained('clients')
                  ->onDelete('cascade');

            $table->primary(['client_id', 'document_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_documents', function (Blueprint $table) {
            //
        });
    }
};
