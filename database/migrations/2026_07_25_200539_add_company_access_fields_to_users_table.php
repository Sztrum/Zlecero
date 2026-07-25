<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractMigration
{
    protected string $table_name = 'users';

    public function up(): void
    {
        Schema::table($this->table_name, static function (Blueprint $table) {
            $table->uuid('company_id')->nullable()->after('id');
            $table->string('role', 24)->default('owner')->after('password');
            $table->string('status', 24)->default('active')->after('role');
            $table->timestamp('invited_at')->nullable()->after('status');
            $table->timestamp('deactivated_at')->nullable()->after('invited_at');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->nullOnDelete();
            $table->index(['company_id', 'role']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table($this->table_name, static function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropIndex(['company_id', 'role']);
            $table->dropIndex(['company_id', 'status']);
            $table->dropColumn([
                'company_id',
                'role',
                'status',
                'invited_at',
                'deactivated_at',
            ]);
        });
    }
};
