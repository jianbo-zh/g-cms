<?php

namespace App\Http\Controllers\Thing;

use App\Services\Thing\Service\FieldService;
use App\Services\Thing\Service\OperationService;
use App\Services\Thing\Service\StateService;
use Illuminate\Support\Facades\Route;


class StateController extends Controller
{
    /**
     * @var StateService
     */
    protected $stateService;

    /**
     * @var FieldService
     */
    protected $fieldService;

    /**
     * @var OperationService
     */
    protected $operationService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->stateService = StateService::instance();
        $this->fieldService = FieldService::instance();
        $this->operationService = OperationService::instance();
    }

    /**
     * 事物状态列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexThingStates()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $states = $this->stateService->getStates('123', $thingId);

            return view('platform.thing.state.indexThingStates', [
                'appId' => $appId,
                'thingId' => $thingId,
                'states' => $states
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建事物状态表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createThingState()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $fields = $this->fieldService->getFields('123', $thingId);

            $symbols = $this->stateService->getStateConditionSymbols('123');

            return view('platform.thing.state.createThingState', [
                'appId' => $appId,
                'thingId' => $thingId,
                'fields' => $fields,
                'symbols' => $symbols,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑事物状态表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editThingState()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $stateId = Route::input('stateId');

            $this->setOperationContext($appId, $thingId);

            $fields = $this->fieldService->getFields('123', $thingId);

            $symbols = $this->stateService->getStateConditionSymbols('123');

            $state = $this->stateService->getState('123', $stateId);

            return view('platform.thing.state.editThingState', [
                'appId'     => $appId,
                'thingId'   => $thingId,
                'fields'    => $fields,
                'symbols'   => $symbols,
                'state'     => $state
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 事物操作列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexThingStateOperations()
    {
        try{
            $appId      = Route::input('appId');
            $thingId    = Route::input('thingId');
            $stateId    = Route::input('stateId');

            $this->setOperationContext($appId, $thingId);

            $operations = $this->operationService->getStateOperations('123', $stateId);
            $notBelongOperations = $this->operationService->getOperationsNotBelongState('123', $thingId,
                $stateId);

            return view('platform.thing.state.indexThingStateOperations', [
                'appId'         => $appId,
                'thingId'       => $thingId,
                'stateId'       => $stateId,
                'operations'    => $operations,
                'notBelongOperations'     => $notBelongOperations
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
