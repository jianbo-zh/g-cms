@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            编辑事物
                        </div>
                        <div class="card-body row">
                            <div class="col-md-8">
                                <form id="updateThingForm">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="name">名称</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="name" name="name" value="{{ $thing['name'] }}" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-right" for="description">描述</label>
                                        <div class="col-md-9">
                                            <textarea rows="3" class="form-control" id="description" name="description">{{ $thing['description'] }}</textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="updateThingSubmit">
                                        <span class="ladda-label">提 交</span>
                                        <span class="ladda-spinner"></span>
                                    </button>
                                </div>
                            </div>
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
            $('#updateThingSubmit').click(function(){
                let ladda = Ladda.create(this).start();
                let data = $('#updateThingForm').serializeJSON();

                z_ajax('put', '{{ route('updateThing', ['appId'=>$appId, 'thingId'=>$thing['id']]) }}', data, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route('indexThings', ['appId'=>$appId]) }}';
                        });
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                })
            });
        })
    </script>
@endpush