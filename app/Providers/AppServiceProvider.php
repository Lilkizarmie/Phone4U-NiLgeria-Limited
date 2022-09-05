<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Schema::defaultStringLength(191);
        if (file_exists(storage_path('installed'))) {
            $all_menus = Menu::where('is_static', INACTIVE)->with('submenus')->latest()->get();
            $allsettings = allsetting();
            view()->share(['all_menus' => $all_menus, 'allsettings' => $allsettings]);
        }
    }
}
