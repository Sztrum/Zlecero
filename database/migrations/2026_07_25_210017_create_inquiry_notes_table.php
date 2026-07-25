<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'inquiry_notes';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('inquiry_id');
            $table->uuid('author_user_id')->nullable();
            $table->longText('body');
            $table->boolean('is_internal')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->cascadeOnDelete();
            $table->foreign('author_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['company_id', 'inquiry_id']);
            $table->index(['company_id', 'created_at']);
        });
    }
};
