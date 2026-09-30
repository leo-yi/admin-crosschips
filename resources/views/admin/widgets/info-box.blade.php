<div {!! $attributes !!}>
    <div class="inner">
        <h3 style="color: floralwhite">{{ $info }}</h3>
        <p>{{ $name }}</p>
        @if($sub)
            <p class="small mb-0" style="opacity: .85">{{ $sub }}</p>
        @endif
    </div>
    <div class="icon">
        <i class="fa fa-{{ $icon }}"></i>
    </div>
    @if($link)
        <a href="{{ $link }}" class="small-box-footer">
            查看详情&nbsp;
            <i class="fa fa-arrow-circle-right"></i>
        </a>
    @endif
</div>
