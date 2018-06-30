<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class AsideComposer
{

    public function compose(View $view)
    {
        $view->with('count', '');
    }

}