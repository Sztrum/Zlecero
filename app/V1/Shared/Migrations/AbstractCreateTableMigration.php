<?php

declare(strict_types=1);

namespace App\V1\Shared\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

abstract class AbstractCreateTableMigration extends Migration
{
    protected string $table_name;

    abstract public function up(): void;

    public function down(): void
    {
        Schema::dropIfExists($this->table_name);
    }
}
