<span class="l">
        @forelse ($handle as $link)
        <a class="btn btn-primary radius" href="javascript:;"
           onclick="{{$link['click']}}">
            <i class="Hui-iconfont">{{$link['icon']}}</i>
            {{$link['title']}}
        </a>

        @empty
        @endforelse
</span>

<script type="text/javascript">
function frame_window_open(title,url,w,h){
    layer_show(title,url,w,h);
}
</script>