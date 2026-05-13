<?php

namespace App\Providers;

use App\Models\MapelMi;
use App\Models\MapelSmp;
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
            'mapel_mi' => MapelMi::class,
            'mapel_smp' => MapelSmp::class,
            // Handle corrupted data (missing backslash)
            'AppModelsSiswaMi' => SiswaMi::class,
            'AppModelsSiswaSmp' => SiswaSmp::class,
            'AppModelsMapelMi' => MapelMi::class,
            'AppModelsMapelSmp' => MapelSmp::class,
        ]);
    }
}
