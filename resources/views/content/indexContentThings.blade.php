@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 应用列表
                        </div>
                        <div class="card-body">
                            <form method="get" action="" class="form-inline table-search">
                                @foreach($queryFields as $queryField)
                                    {!! build_query_html($queryField, $query) !!}
                                @endforeach
                                <div class="form-actions">
                                    <button class="btn btn-primary" type="submit">搜 索</button>
                                    @if($addOperationId)
                                        <a href="{{ route('createContentThing', ['appId'=>$appId, 'thingId'=>$thingId, 'operationId'=>$addOperationId]) }}" class="btn btn-success">新增</a>
                                    @endif
                                </div>
                            </form>
                            <table class="table table-responsive-sm table-bordered">
                                <thead>
                                <tr>
                                    @foreach($content['title'] as $title)
                                        <th>{{ $title }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($content['data'] as $row)
                                    <tr>
                                        @foreach($row as $key => $value)
                                            @if(! $loop->last)
                                                <td>{{ $value }}</td>

                                            @elseif(is_array($value))
                                                <td>
                                                    @foreach($value as $operation)
                                                        @if($operation['operationForm'] === 'command')
                                                            @if($operation['operationType'] === 'delete')
                                                                <button class="btn btn-danger btn-ladda ladda-button destroyThingContent" data-style="zoom-out" data-id="{{ $row['id'] }}">{{ $operation['name'] }}</button>
                                                            @else
                                                                <button class="btn btn-danger btn-ladda ladda-button updateContentThing" data-style="zoom-out" data-id="{{ $row['id'] }}">{{ $operation['name'] }}</button>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('editContentThing', ['appId'=>$appId, 'thingId'=>$thingId,
                                                            'contentId'=>$row['id'], 'operationId'=>$operation['id']]) }}" class="btn btn-primary">{{ $operation['name'] }}</a>
                                                        @endif
                                                    @endforeach
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
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
            $('.destroyThingContent').click(function () {
                let contentId = $(this).attr('data-id');
                let $that = $(this);
                if(confirm('确认删除该事物？')){
                    let ladda = Ladda.create(this).start();
                    let turl = '{{ route('destroyContentThing', ['appId' => $appId, 'thingId' => $thingId, 'contentId'=>'[contentId]']) }}';
                    z_ajax('delete', z_bind_url_params(turl, {'[contentId]':contentId}), null, function (response) {
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