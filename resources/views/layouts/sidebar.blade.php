<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            @foreach($menu as $key => $val)
                @if(! isset($val['action']))
                    <li class="nav-title">{{ $key }}</li>
                    @foreach($val as $key2 => $val2)
                        @if(! isset($val2['action']))
                            @php
                                $includeCurrentAction = false;
                                foreach ($val2 as $key3 => $val3){
                                    if($currentAction == $val3['action']){
                                        $includeCurrentAction = true;
                                        break;
                                    }
                                }
                            @endphp
                            <li class="nav-item nav-dropdown @if($includeCurrentAction) open @endif">
                                <a class="nav-link nav-dropdown-toggle" href="#">
                                    <i class="nav-icon icon-menu"></i> {{ $key2 }}</a>
                                <ul class="nav-dropdown-items">
                                    @foreach($val2 as $key3 => $val3)
                                        <li class="nav-item @if($val3['action']==$currentAction) open @endif">
                                            <a class="nav-link @if($val3['action']==$currentAction) active @endif" href="{{ bind_operation_param($val3['url']) }}">
                                                <i class="nav-icon {{ $val3['icon'] }}"></i> {{ $key3 }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item @if($val2['action']==$currentAction) open @endif">
                                <a class="nav-link @if($val2['action']==$currentAction) active @endif" href="{{ bind_operation_param($val2['url']) }}">
                                    <i class="nav-icon {{ $val2['icon'] }}"></i> {{ $key2 }}</a>
                            </li>
                        @endif
                    @endforeach
                @else
                    <li class="nav-item @if($val['action']==$currentAction) open @endif">
                        <a class="nav-link @if($val['action']==$currentAction) active @endif" href="{{ bind_operation_param($val['url']) }}">
                            <i class="nav-icon {{ $val['icon'] }}"></i> {{ $key }}
                            {{--<span class="badge badge-primary">NEW</span>--}}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>