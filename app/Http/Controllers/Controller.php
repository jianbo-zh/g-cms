<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Route;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * 获取分页信息
     *
     * @param int $total 总数
     * @param array $extraData 额外信息
     * @param int $perPage 每页数量
     * @return \Illuminate\Support\HtmlString
     */
    public function pagination(int $total, array $extraData=[], int $perPage=15)
    {
        $paginator = new LengthAwarePaginator([], $total, 2);

        $paginator->withPath(Route::getCurrentRoute()->uri);

        return $paginator->render('common.pagination', $extraData);
    }

    /**
     * 错误异常响应
     *
     * @param \Exception $e
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function exceptionResponse(\Exception $e)
    {
        return view('errors.500', [
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ]);
    }

}
