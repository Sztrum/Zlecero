<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'offer_items';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('offer_id');
            $table->unsignedSmallInteger('position');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 12, 3);
            $table->string('unit', 20)->default('pcs');
            $table->unsignedInteger('unit_price_cents');
            $table->decimal('tax_rate', 5, 2)->default(23);
            $table->unsignedInteger('net_cents');
            $table->unsignedInteger('tax_cents');
            $table->unsignedInteger('gross_cents');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('offer_id')->references('id')->on('offers')->cascadeOnDelete();
            $table->index(['company_id', 'offer_id']);
        });
    }
};
