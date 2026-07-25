<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'inquiry_messages';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('inquiry_id');
            $table->uuid('customer_id')->nullable();
            $table->uuid('created_by_user_id')->nullable();
            $table->string('direction', 24);
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('subject')->nullable();
            $table->longText('body');
            $table->string('external_message_id')->nullable();
            $table->string('external_thread_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['company_id', 'inquiry_id']);
            $table->index(['company_id', 'external_thread_id']);
            $table->index(['company_id', 'sent_at']);
        });
    }
};
