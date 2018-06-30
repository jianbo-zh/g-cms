<?php

namespace App\Http\Controllers\Thing;

use App\Http\Libraries\OperationContext;
use App\Services\Thing\Service\ThingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class ThingController extends Controller
{
    /**
     * @var ThingService
     */
    protected $thingService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->thingService = ThingService::instance();
    }

    /**
     * 事物列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexThings(Request $request)
    {

        $appId = Route::input('appId');

        $this->setOperationContext($appId);

        $query = [
            'name' => $request->query('name', null)
        ];

        $things = $this->thingService->getThings('123', $appId, $query);

        return view('platform.thing.indexThings', [
            'query'         => $query,
            'appId'         => $appId,
            'things'      => $things
        ]);
    }

    /**
     * 创建应用表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createThing()
    {
        try{
            $appId = Route::input('appId');

            $this->setOperationContext($appId);

            return view('platform.thing.createThing', [
                'appId' => $appId
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑应用表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editThing()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $thing = $this->thingService->getThing('123', $thingId);

            return view('platform.thing.editThing', [
                'appId' => $appId,
                'thing' => $thing
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 事物管理首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manageThing()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            OperationContext::setAppId($appId);
            OperationContext::setThingId($thingId);


            return view('platform.thing.manageThing');

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
