<?php

namespace App\Http\ViewComposers;

use App\Http\Libraries\OperationContext;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class BreadcrumbComposer
{

    /**
     * 获取当前访问路径面包屑
     *
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $historyAccessNodes = OperationContext::getAccessNodes();
        $configAccessNodes = config('navigation.accessNodes');
        $currentAction = Route::currentRouteAction();

        if(isset($configAccessNodes[$currentAction])){
            $nowAccessNodes = [];
            $notCurrentAccessNode = true;
            foreach ($historyAccessNodes as $val) {
                $nowAccessNodes[] = $val;
                if ($val['action'] === $currentAction) {
                    OperationContext::setAccessNodes($nowAccessNodes);
                    $notCurrentAccessNode = false;
                    break;
                }
            }
            if($notCurrentAccessNode){
                OperationContext::addAccessNode([
                    'name'      => $configAccessNodes[$currentAction][0],
                    'action'    => $currentAction,
                ]);
            }
            $historyAccessNodes = OperationContext::getAccessNodes();
        }

        $breadcrumbs = [];
        foreach ($historyAccessNodes as $val){
            $breadcrumbs[] = [
                'name'  => $val['name'],
                'url'   => $val['url'],
            ];
        }

        $breadcrumbMenu = [
//            ['icon'  => 'icon-speech', 'name'  => '', 'url'   => '#'],
//            ['icon'  => 'icon-graph', 'name'  => 'Dashboard', 'url'   => '#'],
//            ['icon'  => 'icon-settings', 'name'  => 'Settings', 'url'   => '#']
        ];

        $view->with('breadcrumbs', $breadcrumbs);
        $view->with('breadcrumbMenu', $breadcrumbMenu);
    }

}