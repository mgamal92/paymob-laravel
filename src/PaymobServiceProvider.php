<?php
namespace MG\Paymob;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use MG\Paymob\Facades\Paymob;

class PaymobServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            // config file.
            __DIR__.'/config/paymob.php' => config_path('paymob.php'),
        ]);
    }
    public function register()
    {
        // PayMob Facede.
        $this->app->singleton('paymob', function () {
            return new Paymob;
        });
    }
}