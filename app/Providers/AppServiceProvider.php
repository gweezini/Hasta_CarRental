<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Vehicle;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            
            $roadTaxAlerts = Vehicle::whereDate('road_tax_expiry', '<=', Carbon::now()->addDays(30))->get();

            $insuranceAlerts = Vehicle::whereDate('insurance_expiry', '<=', Carbon::now()->addDays(30))->get();

            $view->with('roadTaxAlerts', $roadTaxAlerts)
                 ->with('insuranceAlerts', $insuranceAlerts);
        });
    }
}