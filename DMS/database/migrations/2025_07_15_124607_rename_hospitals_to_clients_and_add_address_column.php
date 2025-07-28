<?php
// database/migrations/xxxx_xx_xx_xxxxxx_rename_hospitals_to_clients_and_add_address_column.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('hospitals', 'clients');
        Schema::table('clients', function (Blueprint $table) {
            $table->string('address')->nullable()->after('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('address');
        });
        Schema::rename('clients', 'hospitals');
    }
};
