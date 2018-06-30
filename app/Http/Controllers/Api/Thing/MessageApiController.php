<?php

namespace App\Http\Controllers\Api\Thing;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Thing\PostThingMessageRequest;
use App\Http\Requests\Thing\PutThingMessageRequest;
use App\Services\Thing\Service\MessageService;
use Illuminate\Support\Facades\Route;

/**
 * 事物消息定义相关接口
 *
 * Class ThingApiController
 * @package App\Http\Controllers\Api
 */
class MessageApiController extends Controller
{
    /**
     * @var MessageService
     */
    protected $messageService;

    /**
     * MessageApiController constructor.
     */
    public function __construct()
    {
        $this->messageService = MessageService::instance();
    }

    /**
     * 创建消息定义
     *
     * @param PostThingMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThingMessage(PostThingMessageRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $data = $request->only(['stateId', 'receiverValue', 'content']);

            $message = $this->messageService->addDefinition('123', $data['stateId'], 'role',
                $data['receiverValue'], $data['content']);

            if($message){
                return $this->successResponse();
            }else{
                return $this->failResponse('创建消息定义失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 更新消息定义
     *
     * @param PutThingMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateThingMessage(PutThingMessageRequest $request)
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $messageId = Route::input('messageId');

            $data = $request->only(['stateId', 'receiverValue', 'content']);

            $message = $this->messageService->updateDefinition('123', $messageId, $data['stateId'],
                'role', $data['receiverValue'], $data['content']);

            if($message){
                return $this->successResponse();
            }else{
                return $this->failResponse('创建消息定义失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 删除消息定义
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyThingMessage()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $messageId = Route::input('messageId');

            $result = $this->messageService->deleteDefinition('123', $messageId);

            if($result){
                return $this->successResponse();

            }else{
                return $this->failResponse('删除失败！');
            }

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

}
