<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'companies';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('billing_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address_line')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country_code', 2)->default('PL');
            $table->string('brand_color', 7)->default('#2563eb');
            $table->unsignedInteger('trial_days')->default(14);
            $table->timestamp('trial_started_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->timestamps();
        });
    }
};
