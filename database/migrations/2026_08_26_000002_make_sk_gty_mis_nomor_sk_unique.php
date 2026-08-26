<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEX = 'sk_gty_mis_nomor_sk_index';

    private const UNIQUE = 'sk_gty_mis_nomor_sk_unique';

    public function up(): void
    {
        if (! Schema::hasTable('sk_gty_mis') || $this->hasIndex(self::UNIQUE)) {
            return;
        }

        $duplicatesExist = DB::table('sk_gty_mis')
            ->select('nomor_sk')
            ->groupBy('nomor_sk')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicatesExist) {
            throw new RuntimeException('Unique index nomor_sk tidak dapat dibuat: data duplikat ditemukan di sk_gty_mis.');
        }

        if ($this->hasIndex(self::INDEX)) {
            Schema::table('sk_gty_mis', fn (Blueprint $table) => $table->dropIndex(self::INDEX));
        }

        Schema::table('sk_gty_mis', fn (Blueprint $table) => $table->unique('nomor_sk', self::UNIQUE));
    }

    public function down(): void
    {
        if (! Schema::hasTable('sk_gty_mis')) {
            return;
        }

        if ($this->hasIndex(self::UNIQUE)) {
            Schema::table('sk_gty_mis', fn (Blueprint $table) => $table->dropUnique(self::UNIQUE));
        }

        if (! $this->hasIndex(self::INDEX)) {
            Schema::table('sk_gty_mis', fn (Blueprint $table) => $table->index('nomor_sk', self::INDEX));
        }
    }

    private function hasIndex(string $name): bool
    {
        return collect(Schema::getIndexes('sk_gty_mis'))
            ->contains(fn (array $index) => $index['name'] === $name);
    }
};
