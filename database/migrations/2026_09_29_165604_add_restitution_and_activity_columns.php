<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Existing MySQL databases still have the old ENUM('perdu','restitué','en_attente_de_retrait'),
        // which rejects the 'restitue' value the application writes. Fresh installs already get a string.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE documents MODIFY status VARCHAR(30) NOT NULL DEFAULT 'perdu'");
        }
        DB::table('documents')->where('status', 'restitué')->update(['status' => 'restitue']);

        Schema::table('documents', function (Blueprint $table) {
            $table->string('restitue_a')->nullable(); // who received the document
            $table->timestamp('restitue_at')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->index('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['status']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['handled_by']);
            }

            $table->dropColumn(['restitue_a', 'restitue_at', 'handled_by']);
        });
    }
};
