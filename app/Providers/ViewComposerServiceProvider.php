<?php

namespace App\Providers;

use App\Http\ViewComposers\AsideComposer;
use App\Http\ViewComposers\BreadcrumbComposer;
use App\Http\ViewComposers\FooterComposer;
use App\Http\ViewComposers\HeaderComposer;
use App\Http\ViewComposers\SidebarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.header', HeaderComposer::class);
        View::composer('layouts.sidebar', SidebarComposer::class);
        View::composer('layouts.aside', AsideComposer::class);
        View::composer('layouts.breadcrumb', BreadcrumbComposer::class);
        View::composer('layouts.footer', FooterComposer::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
