<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'inquiries';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('customer_id')->nullable();
            $table->uuid('owner_user_id')->nullable();
            $table->string('source', 32)->default('manual');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 40)->default('new');
            $table->string('priority', 24)->default('normal');
            $table->timestamp('response_due_at')->nullable();
            $table->timestamp('realization_due_at')->nullable();
            $table->timestamp('pickup_due_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('owner_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'priority']);
            $table->index(['company_id', 'owner_user_id']);
            $table->index(['company_id', 'response_due_at']);
            $table->index(['company_id', 'archived_at']);
        });
    }
};
