<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'company_invitations';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignUuid('invited_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('inviting_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }
};
