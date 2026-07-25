<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'inquiry_files';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('inquiry_id');
            $table->uuid('customer_id')->nullable();
            $table->uuid('inquiry_message_id')->nullable();
            $table->uuid('uploaded_by_user_id')->nullable();
            $table->string('source', 32)->default('manual');
            $table->string('disk', 64)->default('local');
            $table->string('stored_path', 1024);
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes');
            $table->string('category', 80)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('inquiry_message_id')->references('id')->on('inquiry_messages')->nullOnDelete();
            $table->foreign('uploaded_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['company_id', 'inquiry_id']);
            $table->index(['company_id', 'source']);
            $table->index(['company_id', 'original_name']);
        });
    }
};
