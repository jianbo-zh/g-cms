<?php

namespace App\Http\Controllers\Thing;

use App\Services\Thing\Service\MessageService;
use App\Services\Thing\Service\StateService;
use App\Services\User\Service\RoleService;
use Illuminate\Support\Facades\Route;


class MessageController extends Controller
{
    /**
     * @var MessageService
     */
    protected $messageService;

    /**
     * @var StateService
     */
    protected $stateService;

    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->messageService = MessageService::instance();
        $this->stateService = StateService::instance();
        $this->roleService = RoleService::instance();
    }

    /**
     * 消息定义列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexThingMessages()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $messages = $this->messageService->getDefinitions('123', $thingId);

            foreach ($messages as $key => $message){
                $state = $this->stateService->getState('123', $message['stateId']);
                if(!empty($state)){
                    $messages[$key]['stateName'] = $state['name'];
                }
                if($message['receiverType'] === 'role'){
                    $role = $this->roleService->getAppRole('123', $appId, $message['receiverValue']);
                    $messages[$key]['roleName'] = $role['name'];
                }
            }

            return view('platform.thing.message.indexThingMessages', [
                'appId'     => $appId,
                'thingId'   => $thingId,
                'messages'  => $messages,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建消息表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createThingMessage()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId);

            $states = $this->stateService->getStates('123', $thingId);
            $roles = $this->roleService->getAppRoles('123', $appId);

            return view('platform.thing.message.createThingMessage', [
                'appId'     => $appId,
                'thingId'   => $thingId,
                'states'  => $states,
                'roles'  => $roles,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑消息表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editThingMessage()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $messageId = Route::input('messageId');

            $this->setOperationContext($appId, $thingId);

            $states = $this->stateService->getStates('123', $thingId);
            $roles = $this->roleService->getAppRoles('123', $appId);
            $message = $this->messageService->getDefinition('123', $messageId);

            return view('platform.thing.message.editThingMessage', [
                'appId'     => $appId,
                'thingId'   => $thingId,
                'states'  => $states,
                'roles'  => $roles,
                'message'  => $message,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
