<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'company_management_invitations';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invited_by')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('inviting_company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignUuid('invited_company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('token');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }
};
