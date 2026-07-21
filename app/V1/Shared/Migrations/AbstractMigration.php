<?php

declare(strict_types=1);

namespace App\V1\Shared\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

abstract class AbstractMigration extends Migration
{
    protected string $table_name;

    abstract public function up(): void;

    abstract public function down(): void;

    public function hasForeignKey(string $table, string $column): bool
    {
        foreach (Schema::getForeignKeys($table) as $foreignKey) {
            if (!is_array($foreignKey)) {
                continue;
            }

            $columns = $foreignKey['columns'] ?? [];

            if (!is_array($columns)) {
                continue;
            }

            if (in_array($column, $columns, true)) {
                return true;
            }
        }

        return false;
    }
}
