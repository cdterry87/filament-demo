<?php

namespace App\Providers;

use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
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
        // Make sure models are treated as guarded = [];
        Model::unguard();
        
        // Set Filament defaults
        CreateAction::configureUsing(function (CreateAction $action) {
            return $action->slideOver();
        });
    }
}
