<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'companies';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->boolean('is_international')->default(false);
            $table->string('country', 2);
            $table->string('vat_number_type');
            $table->string('vat_number_value')->unique();
            $table->string('regon')->nullable();
            $table->string('company_type');
            $table->boolean('vat_number_verified_by_gus')->default(false);
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('voivodeship')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('invoice_email')->nullable();
            $table->timestamps();
        });
    }
};
