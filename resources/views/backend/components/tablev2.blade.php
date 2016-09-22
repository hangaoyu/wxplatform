<!-- 按钮栏 结束 -->
<div class="mt-20">
    <table class="table table-border table-bordered table-hover table-bg">
        <thead>
        @if(!empty($table['fields']) && is_array($table['fields']))
            <tr>
                @foreach($table['fields'] as $field)
                    <th>{{$field}}</th>
                @endforeach
                <th>管理操作</th>
            </tr>
        @endif
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                @foreach($table['fields'] as $key =>$field)
                    <td>{{$item->$key}}</td>
                @endforeach
                <td>
                    @foreach($table['handle'] as $button)
                        @if($button['type'] == 'edit')
                            <a href="{{route($button['route'],['id'=>$item->id])}}"
                               class="btn btn-info btn-flat">
                                {{$button['title']}}
                            </a>
                        @endif
                        @if($button['type'] == 'delete')
                            <a class="btn btn-danger btn-flat"
                               data-url="{{route($button['route'],['id'=>$item->id])}}"
                               data-toggle="modal"
                               data-target="#delete-modal"
                            >
                                删除
                            </a>
                            @section("after.js")
                                @include('backend.components.modal.delete',['title'=>'操作提示','content'=>'你确定要删除这条菜单吗?'])
                            @endsection
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@if($data->render())
    <div class="box-footer clearfix">
        <div id="pageDiv" style="margin-top:5px; text-align:left;"></div>
    </div>
    <script type="text/javascript">
        laypage({
            cont: 'pageDiv',
            pages: {!! $data->lastPage() !!}, //可以叫服务端把总页数放在某一个隐藏域，再获取。假设我们获取到的是18
            curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
                var page = location.search.match(/page=(\d+)/);
                return page ? page[1] : 1;
            }(),
            jump: function(e, first){ //触发分页后的回调
                if(!first){ //一定要加此判断，否则初始时会无限刷新
                    location.href = '?page='+e.curr;
                }
            }
        })

    </script>
@endif

</div>
<!-- 表格数据 结束 -->