@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> 编辑平台角色
                        </div>
                        <div class="card-body">
                            <form method="post" class="form-horizontal" id="edit-role-form">
                                <input type="hidden" name="id" value="{{ $role['id'] }}" />
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label" for="state">名称</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $role['name'] }}" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label" for="description">描述</label>
                                    <div class="col-md-6">
                                        <textarea rows="3" class="row-span form-control" id="description" name="description">{{ $role['description'] }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">状态</label>
                                    <div class="col-md-6">
                                        <label class="switch switch-label switch-outline-primary-alt">
                                            <input type="checkbox" class="switch-input" @if($role['state']===1) checked="checked" @endif name="state" value="1">
                                            <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label" for="description">权限</label>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @foreach($permissions as $key => $module)
                                                    <ul class="nav nav-tabs" id="pills-tab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link @if($key===0) active show @endif " id="pills-module-tab-{{ $key }}" data-toggle="pill" href="#pills-module-{{ $key }}" role="tab" aria-controls="pills-module-{{ $key }}"
                                                               @if($key===0) aria-selected="true" @else aria-selected="false" @endif >{{ $module['name'] }}</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="pills-tabContent">
                                                        <div class="tab-pane fade @if($key===0) active show @endif " id="pills-module-{{ $key }}" role="tabpanel" aria-labelledby="pills-module-tab-{{ $key }}">
                                                            @foreach($module['groups'] as $key2 => $group)
                                                                <div class="mb-2">
                                                                    <h6 style="cursor:default;">{{ $group['name'] }}</h6>
                                                                    <div class="col-form-label">
                                                                        @foreach($group['perms'] as $key3 => $perm)
                                                                            <div class="form-check form-check-inline mr-1">
                                                                                <input class="form-check-input" type="checkbox" @if(in_array($key3, $role['perms'])) checked="checked" @endif name="perms[]" id="inline-checkbox-{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}" value="{{ $key3 }}">
                                                                                <label class="form-check-label" for="inline-checkbox-{{ $loop->parent->parent->index }}-{{ $loop->parent->index }}-{{ $loop->index }}">{{ $perm }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-actions col-md-10">
                                    <button class="btn btn-primary btn-ladda ladda-button" data-style="zoom-out" id="edit-role-submit">
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
    $(function () {
        $('h6').click(function () {
            let $inputs = $(this).next('div').find('input[type="checkbox"]');
            if($inputs.first().is(":checked")){
                $inputs.prop("checked",false);
            }else{
                $inputs.prop("checked",true);
            }
        });

        $('#edit-role-submit').click(function () {
            let formData = $('#edit-role-form').serializeJSON();
            let ladda = Ladda.create(this).start();
            z_ajax('put', '{{ route("updatePlatformRole") }}', formData, function (response) {
                let data = response.data;
                if(data.code === 0){
                    z_notify_success(data.message ? data.message : '操作成功！', function () {
                        location.href = '{{ route("indexPlatformRoles") }}';
                    });
                }else{
                    z_notify_fail(data.message ? data.message : '操作失败！');
                }
                ladda.stop();
            });
        });
    });
</script>
@endpush