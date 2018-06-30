<!-- Breadcrumb-->
<ol class="breadcrumb">
    @foreach($breadcrumbs as $val)
        @if($loop->last)
            <li class="breadcrumb-item active">{{ $val['name'] }}</li>
        @else
            <li class="breadcrumb-item">
                <a href="{{ $val['url'] }}">{{ $val['name'] }}</a>
            </li>
        @endif
    @endforeach

    @if(!empty($breadcrumbMenu))
        <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group">
                @foreach($breadcrumbMenu as $value)
                    <a class="btn" href="{{ $value['url'] }}">
                        <i class="{{ $value['icon'] }}"></i>  {{ $value['name'] }}</a>
                @endforeach
            </div>
        </li>
    @endif
</ol>