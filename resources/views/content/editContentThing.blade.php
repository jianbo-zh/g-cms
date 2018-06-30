@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-accent-info">
                        <div class="card-header">
                            编辑事物内容
                        </div>
                        <div class="card-body row">
                            <div class="col-md-12">
                                <form id="updateThingContentForm">
                                    @foreach($fields as $field)
                                        {!! build_detail_html($field, $content) !!}
                                    @endforeach
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group-actions col-md-9">
                                    <button class="btn btn-success btn-ladda ladda-button" data-style="zoom-out" id="updateThingContentSubmit">
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
    @if(count($richTextFields) > 0)
        <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('vendor/tinymce/plugins.min.js') }}"></script>

        <script>
            @foreach($richTextFields as $field)
            tinymce.init({
                selector: "textarea#{{ $field['name'] }}",
            });
            @endforeach
        </script>
    @endif

    <script>
        $(function(){
            $('#updateThingContentSubmit').click(function(){
                // save rich text
                for (let edId in tinymce.editors) {
                    if (tinymce.editors.hasOwnProperty(edId)) {
                        tinymce.editors[edId].save();
                    }
                }

                let ladda = Ladda.create(this).start();
                let data = $('#updateThingContentForm').serializeJSON();

                z_ajax('put', '{{ route('updateContentThing', ['appId'=>$appId, 'thingId'=>$thingId, 'contentId'=>$content['id'],
                'operationId'=>$operationId]) }}', data, function (response) {
                    let data = response.data;
                    if(data.code === 0){
                        z_notify_success(data.message ? data.message : '操作成功！', function () {
                            location.href = '{{ route('indexContentThings', ['appId'=>$appId, 'thingId'=>$thingId]) }}';
                        });
                    }else{
                        z_notify_fail(data.message ? data.message : '操作失败！');
                    }
                    ladda.stop();
                })
            });
        })
    </script>
@endpush