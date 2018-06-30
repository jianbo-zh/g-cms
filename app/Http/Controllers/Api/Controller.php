<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * 返回成功
     *
     * @param array|null $data 返回数据
     * @param string $message 返回消息
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse(array $data=null, string $message='')
    {
        return response()->json([
            'code'          => 0,
            'state'         => 'success',
            'data'          => $data,
            'message'       => $message
        ]);
    }

    /**
     * 返回错误响应
     *
     * @param string $message 错误信息描述
     * @param int $code 错误码
     * @param null $errors 错误数据
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function failResponse(string $message, int $code=999, $errors=null)
    {
        if($code === 0){
            throw new \Exception('失败响应代码不能为0！');
        }
        return response()->json([
            'code'      => $code,
            'state'     => 'fail',
            'message'   => $message,
            'errors'    => $errors
        ]);
    }

    /**
     * 返回异常响应
     *
     * @param \Exception $e 捕获异常
     * @param null $errors 额外的错误信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function exceptionResponse(\Exception $e, $errors=null)
    {
        return response()->json([
            'code'      => $e->getCode() ? : 1,
            'state'     => 'fail',
            'message'   => $e->getMessage(),
            'errors'    => $errors
        ]);
    }



}
