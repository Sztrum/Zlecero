<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'offers';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('inquiry_id');
            $table->uuid('customer_id')->nullable();
            $table->uuid('owner_user_id')->nullable();
            $table->string('number', 40);
            $table->string('status', 32)->default('draft');
            $table->string('currency', 3)->default('PLN');
            $table->date('issue_date');
            $table->date('valid_until');
            $table->unsignedSmallInteger('payment_due_days')->default(7);
            $table->unsignedInteger('delivery_cost_cents')->default(0);
            $table->string('discount_type', 16)->nullable();
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('deposit_percent', 5, 2)->default(0);
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('subtotal_net_cents')->default(0);
            $table->unsignedInteger('discount_cents')->default(0);
            $table->unsignedInteger('tax_cents')->default(0);
            $table->unsignedInteger('total_gross_cents')->default(0);
            $table->unsignedInteger('deposit_cents')->default(0);
            $table->string('pdf_disk', 64)->nullable();
            $table->string('pdf_path', 1024)->nullable();
            $table->string('pdf_original_name')->nullable();
            $table->timestamp('pdf_generated_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('owner_user_id')->references('id')->on('users')->nullOnDelete();
            $table->unique(['company_id', 'number']);
            $table->index(['company_id', 'inquiry_id']);
            $table->index(['company_id', 'status']);
        });
    }
};
