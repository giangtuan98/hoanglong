<?php

namespace App\Providers;

use App\Helpers\SidebarHelper;
use App\Models\Brand;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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
        view()->composer(['web.booking.index','web.layout.search', 'web.home.price-table', 'admin-page.route'], function ($view) {
            $nextDate = Carbon::now()->addDays(0)->format('d-m-Y');
            $key = md5(vsprintf('%s.%s.%s', [
                'web',
                'all',
                'provinces'
            ]));

            // $provinces = Cache::remember($key, 1000, function () {
            //     return Province::all();
            // });
            $provinces = Province::all();

            $brands = Brand::active()->get();
            $view->with(['provinces'=>$provinces, 'nextdate'=>$nextDate, 'brands' => $brands]);
        });

        view()->composer(['admin.layout.top_nav'], function ($view) {
            $admin = getAuthAdmin();

            $brand = $admin->brand;
            $notifications = $brand ? $brand->notifications()->where('data->type', 'ticket')->get() : collect([]);
            $view->with(['notifications'=>$notifications]);
        });

        view()->composer(['admin.layout.left_sidebar'], function ($view) {
            
            // $admin = getAuthAdmin();

            // $key = md5(vsprintf('%s.%s.%s', [
            //     $admin->role->name,
            //     'show',
            //     'sidebar'
            // ]));
            // $sidebar = Cache::remember($key, 1000, function () {
            //     return SidebarHelper::getSideBar();
            // });
            $sidebar = SidebarHelper::getSideBar();
            $view->with(['sidebar'=>$sidebar]);
        });
    }
}
