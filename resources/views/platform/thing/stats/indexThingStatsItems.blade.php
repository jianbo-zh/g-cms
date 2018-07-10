@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 统计定义
                        </div>
                        <div class="card-body">
                            <div class="table-search">
                                <a href="{{ route('createThingStatsItem', ['appId'=>$appId, 'thingId'=>$thingId]) }}" class="btn btn-primary">新增</a>
                            </div>

                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($statsItems as $stats)
                                    <tr>
                                        <td>{{ $stats['name'] }}</td>
                                        <td>
                                            <a href="{{ route('showThingStatsItem', ['appId'=>$appId, 'thingId'=>$thingId, 'stateItemId'=>$stats['id']]) }}" class="btn btn-success">图表</a>
                                            <a href="{{ route('editThingStatsItem', ['appId'=>$appId, 'thingId'=>$thingId, 'statsItemId'=>$stats['id']]) }}" class="btn btn-primary">编辑</a>
                                            <button class="btn btn-danger btn-ladda ladda-button destroyThingStatsButton" data-style="zoom-out" data-id="{{ $stats['id'] }}">刪除</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function(){

            $('.destroyThingStatsButton').click(function () {
                let statsItemId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该事物？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyThingStatsItem', ['appId' => $appId, 'thingId' => $thingId, 'statsItemId'=>'[statsItemId]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[statsItemId]':statsItemId}), null, function (response) {
                        let data = response.data;
                        if(data.code === 0){
                            z_notify_success(data.message ? data.message : '操作成功！');
                            $that.parents('tr').remove();
                        }else{
                            z_notify_fail(data.message ? data.message : '操作失败！');
                        }
                        ladda.stop();
                    })
                }
            });
        })
    </script>
@endpush