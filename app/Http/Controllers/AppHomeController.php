<?php

namespace App\Http\Controllers;


class AppHomeController extends Controller
{

    /**
     * 应用首页展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('apphome');
    }
}
