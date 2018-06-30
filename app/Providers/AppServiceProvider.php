<?php

namespace App\Providers;

use App\Http\Libraries\OperationContext;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 结合转换成小驼峰数组
        Collection::macro('toArrayWithCamelKey', function () {
            $array = $this->toArray();
            return array_snake_to_camel($array);
        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OperationContext::class, function ($app) {
            return new OperationContext();
        });
    }
}
