<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class FooterComposer
{

    public function compose(View $view)
    {
        $view->with('count', '');
    }

}