@include('admin.layout.head')
<div class="layuimini-container">
    <div class="layuimini-main" id="app">
        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this" data-group="mall_set">商城设置</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    @include("admin.mall/files/set")
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layout.foot')
