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
                            <div id="main" style="width: 600px;height:400px;"></div>
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
    <script src="{{ asset('js/echarts.min.js') }}"></script>
    <script>
        {!! build_stats_chart_js('main', $chartType, (array)$chartOption, $dataSet) !!}
    </script>
@endpush