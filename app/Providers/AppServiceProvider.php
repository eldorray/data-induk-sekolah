<?php

namespace App\Providers;

use App\Models\SiswaMi;
use App\Models\SiswaSmp;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Morph map for polymorphic relationships
        Relation::enforceMorphMap([
            'siswa_mi' => SiswaMi::class,
            'siswa_smp' => SiswaSmp::class,
            // Handle corrupted data (missing backslash)
            'AppModelsSiswaMi' => SiswaMi::class,
            'AppModelsSiswaSmp' => SiswaSmp::class,
        ]);
    }
}
