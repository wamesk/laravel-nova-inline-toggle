<?php

namespace Wame\InlineToggle;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Wame\InlineToggle\Http\Controllers\InlineToggleController;

class FieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('inline-toggle', __DIR__.'/../dist/js/field.js');
            Nova::style('inline-toggle', __DIR__.'/../dist/css/field.css');
        });

        $this->app->booted(function () {
            $this->routes();
        });
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/inline-toggle')
            ->group(function () {
                Route::post('/update/{resource}', [InlineToggleController::class, 'update']);
            });
    }

    public function register(): void
    {
        //
    }
}
