<?php

namespace App\Http\Controllers\Content;

use App\Http\Libraries\OperationContext;
use App\Services\App\Service\AppService;
use App\Services\Thing\Service\FieldService;
use App\Services\Thing\Service\OperationService;
use App\Services\Thing\Service\StateService;
use App\Services\Thing\Service\ThingService;
use App\Services\User\Service\RoleService;
use App\Services\User\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class ContentThingController extends Controller
{
    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var ThingService
     */
    protected $thingService;

    /**
     * @var FieldService
     */
    protected $fieldService;

    /**
     * @var OperationService
     */
    protected $operationService;

    /**
     * @var StateService
     */
    protected $stateService;

    /**
     * ThingController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->roleService = RoleService::instance();
        $this->userService = UserService::instance();
        $this->thingService = ThingService::instance();
        $this->fieldService = FieldService::instance();
        $this->operationService = OperationService::instance();
        $this->stateService = StateService::instance();
    }

    /**
     * 事物内容列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexContentThings(Request $request)
    {
        try{
            $authUser = Auth::user();
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');

            $this->setOperationContext($appId, $thingId, true);

            $query = $request->query();

            $queryFields = $this->thingService->getThingContentQueryFields('123', $thingId);

            $contentsWithState = $this->thingService->getThingContentsWithState('123', $thingId);
            $contentsWithState['title'][] = '操作';

            $userId = $authUser->getAuthIdentifier();

            $userOperations = $this->operationService->getOperationsOfUser('123', $appId, $thingId, $userId);

            $addOperationId = null;
            $userOperationMap = [];
            foreach ($userOperations as $operation){
                $userOperationMap[$operation['id']] = $operation;
                if($operation['operationType'] === 'add'){
                    $addOperationId = $operation['id'];
                }
            }

            $state2OperationMap = [];
            $thingState2Operations = $this->stateService->getStateAndOperationRelationOfThing($thingId);
            foreach ($thingState2Operations as $value){
                if(!isset($state2OperationMap[$value['stateId']])){
                    $state2OperationMap[$value['stateId']] = [];
                }
                if(!in_array($value['operationId'], $state2OperationMap[$value['stateId']])){
                    $state2OperationMap[$value['stateId']][] = $value['operationId'];
                }
            }

            foreach ($contentsWithState['data'] as $key => $data){
                $data['_operations'] = [];
                if(!empty($data['_stateIds'])){
                    foreach ($data['_stateIds'] as $stateId){
                        if(! empty($state2OperationMap[$stateId])){
                            foreach ($state2OperationMap[$stateId] as $operationId){
                                if(!empty($userOperationMap[$operationId])){
                                    $data['_operations'][] = $userOperationMap[$operationId];
                                }
                            }
                        }
                    }
                }
                unset($data['_stateIds']);
                $contentsWithState['data'][$key] = $data;
            }

            return view('content.indexContentThings', [
                'appId' => $appId,
                'thingId' => $thingId,
                'addOperationId' => $addOperationId,
                'queryFields' => $queryFields,
                'content' => $contentsWithState,
                'query' => $query
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 创建动态事物内容表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createContentThing()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $operationId = Route::input('operationId');

            $this->setOperationContext($appId, $thingId, true);

            // 获取新增操作的字段
            $fields = $this->fieldService->getFieldsOfOperation('123', $operationId);
            $richTextFields = [];
            foreach ($fields as $field){
                if($field['showType'] === 'richtext'){
                    $richTextFields[] = $field;
                }
            }

            return view('content.createContentThing', [
                'appId' => $appId,
                'thingId' => $thingId,
                'operationId' => $operationId,
                'fields' => $fields,
                'richTextFields' => $richTextFields,
            ]);


        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    /**
     * 编辑动态事物内容表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editContentThing()
    {
        try{
            $appId = Route::input('appId');
            $thingId = Route::input('thingId');
            $contentId = Route::input('contentId');
            $operationId = Route::input('operationId');

            $this->setOperationContext($appId, $thingId, true);

            $fields = $this->fieldService->getFieldsOfOperation('123', $operationId);
            $richTextFields = [];
            foreach ($fields as $field){
                if($field['showType'] === 'richtext'){
                    $richTextFields[] = $field;
                }
            }

            $content = $this->thingService->getThingContent('123', $thingId, $contentId);

            return view('content.editContentThing', [
                'appId' => $appId,
                'thingId' => $thingId,
                'operationId' => $operationId,
                'fields' => $fields,
                'richTextFields' => $richTextFields,
                'content' => $content,
            ]);

        }catch (\Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
