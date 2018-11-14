<?php

namespace App\Providers;
// api auth
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

     // api auth
     protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
     ];
    // api auth end

    public function boot()
    {
        Schema::defaultStringLength(191);
        // $Account_Holder_Name = ;
        view()->share('Account_Holder_Name', 'PT.Zuaragan Indonesia');
        // api auth
        $this->registerPolicies();

        Passport::routes();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
