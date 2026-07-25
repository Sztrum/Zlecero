<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'orders';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('inquiry_id')->nullable();
            $table->uuid('offer_id');
            $table->uuid('customer_id')->nullable();
            $table->uuid('owner_user_id')->nullable();
            $table->string('number', 40);
            $table->string('status', 32)->default('new');
            $table->string('currency', 3)->default('PLN');
            $table->date('accepted_date');
            $table->date('payment_due_date')->nullable();
            $table->date('realization_due_date')->nullable();
            $table->date('pickup_due_date')->nullable();
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('subtotal_net_cents')->default(0);
            $table->unsignedInteger('discount_cents')->default(0);
            $table->unsignedInteger('tax_cents')->default(0);
            $table->unsignedInteger('total_gross_cents')->default(0);
            $table->unsignedInteger('deposit_cents')->default(0);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->nullOnDelete();
            $table->foreign('offer_id')->references('id')->on('offers')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('owner_user_id')->references('id')->on('users')->nullOnDelete();
            $table->unique(['company_id', 'number']);
            $table->unique(['company_id', 'offer_id']);
            $table->index(['company_id', 'status']);
        });
    }
};
