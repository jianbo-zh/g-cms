@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 消息定义列表
                        </div>
                        <div class="card-body">
                            <div class="table-search">
                                <a href="{{ route('createThingMessage', ['appId'=>$appId, 'thingId'=>$thingId]) }}" class="btn btn-primary">新增</a>
                            </div>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    <th>描述</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($messages as $message)
                                    <tr>
                                        <td>当状态为 [ <strong>{{ $message['stateName'] }}</strong> ] 时，发送给 [ <strong>{{ $message['roleName'] }}</strong> ]，“{{ $message['content'] }}”消息</td>
                                        <td>
                                            <a href="{{ route('editThingMessage', ['appId'=>$appId, 'thingId'=>$thingId, 'messageId'=>$message['id']]) }}" class="btn btn-primary">编辑</a>
                                            <button class="btn btn-danger btn-ladda ladda-button destroyThingMessageButton" data-style="zoom-out" data-id="{{ $message['id'] }}">刪除</button>
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
            $('.destroyThingMessageButton').click(function () {
                let thingId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该事物？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyThingMessage', ['appId' => $appId, 'thingId' => $thingId, 'messageId'=>'[msgId]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[msgId]':thingId}), null, function (response) {
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