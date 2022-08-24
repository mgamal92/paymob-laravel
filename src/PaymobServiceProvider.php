<?php
namespace MG\Paymob;

use Illuminate\Support\ServiceProvider;

class PaymobServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            // config file.
            __DIR__.'/config/paymob.php' => config_path('paymob.php'),
        ]);
    }
    public function register()
    {
        # code...
    }
}