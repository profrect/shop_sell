@include('admin.layout.head')
<link rel="stylesheet" href="/static/admin/css/welcome.css?v={{$version}}" media="all">
<div class="layui-layout layui-padding-2">
    <div class="layui-layout-admin">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md8 ">
                <div class="layui-row layui-col-space10">
                    <div class="layui-col-md6 ">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fa fa-warning icon"></i>数据统计</div>
                            <div class="layui-card-body">
                                <div class="welcome-module">
                                    <div class="layui-row layui-col-space10">
{{--                                        <div class="layui-col-xs6">--}}
{{--                                            <div class="layui-panel">--}}
{{--                                                <div class="layui-card-body">--}}
{{--                                                    <span class="layui-badge layui-bg-cyan pull-right ">实时</span>--}}
{{--                                                    <div class="panel-content">--}}
{{--                                                        <h5>用户统计</h5>--}}
{{--                                                        <h1>1234</h1>--}}
{{--                                                        <h6>当前分类总记录数</h6>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="layui-col-xs6">--}}
{{--                                            <div class="layui-panel">--}}
{{--                                                <div class="layui-card-body">--}}
{{--                                                    <span class="layui-badge layui-bg-purple pull-right ">实时</span>--}}
{{--                                                    <div class="panel-content">--}}
{{--                                                        <h5>商品统计</h5>--}}
{{--                                                        <h1>1234</h1>--}}
{{--                                                        <h6>当前分类总记录数</h6>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="layui-col-xs6">--}}
{{--                                            <div class="layui-panel">--}}
{{--                                                <div class="layui-card-body ">--}}
{{--                                                    <span class="layui-badge layui-bg-orange pull-right ">实时</span>--}}
{{--                                                    <div class="panel-content">--}}
{{--                                                        <h5>浏览统计</h5>--}}
{{--                                                        <h1>1234</h1>--}}
{{--                                                        <h6>当前分类总记录数</h6>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="layui-col-xs6">--}}
{{--                                            <div class="layui-panel">--}}
{{--                                                <div class="layui-card-body ">--}}
{{--                                                    <span class="layui-badge layui-bg-red pull-right ">实时</span>--}}
{{--                                                    <div class="panel-content">--}}
{{--                                                        <h5>订单统计</h5>--}}
{{--                                                        <h1>1234</h1>--}}
{{--                                                        <h6>当前分类总记录数</h6>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6 ">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fa fa-credit-card icon icon-blue"></i>快捷入口</div>
                            <div class="layui-card-body">
                                <div class="welcome-module">
                                    <div class="layui-row layui-col-space10">

                                        <div class="swiper mySwiper">
                                            <div class="swiper-wrapper">
                                                @foreach($quicks as $value)

                                                    <div class="swiper-slide">
                                                        @foreach($value as $vo)

                                                            <div class="layui-col-xs3 layuimini-qiuck-module">
                                                                <a layuimini-content-href="{{__url($vo['href'])}}" data-title="{{$vo['title']}}">
                                                                    <i class="{{$vo['icon']}}"></i>
                                                                    <cite>{{$vo['title']}}</cite>
                                                                </a>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                        <div class="swiper-pagination"></div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="layui-col-md12 ">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fa fa-line-chart icon"></i>报表统计</div>
                            <div class="layui-card-body">
                                <div id="echarts-records" style="width: 100%;min-height:500px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md4 ">

                <div class="layui-card">
                    <div class="layui-card-header"><i class="fa fa-fire icon"></i>版本信息</div>
                    <div class="layui-card-body layui-text">
                        <table class="layui-table">
                            <colgroup>
                                <col width="150">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>框架名称</td>
                                <td>
                                    <button type="button" class="layui-btn layui-btn-xs layui-btn-primary">Laravel</button>
                                </td>
                            </tr>
                            <tr>
                                <td>分支版本</td>
                                <td>
                                    <button type="button" class="layui-btn layui-btn-xs layui-btn-primary">{{$versions['branch']??"main"}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Laravel版本</td>
                                <td>
                                    <button type="button" class="layui-btn layui-btn-xs layui-btn-primary">{{$versions['laravelVersion']??''}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Config配置缓存</td>
                                <td>
                                    <button type="button" class="layui-btn layui-btn-xs layui-btn-primary">{{$versions['configIsCached']?'已开启':'未开启'}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>PHP版本</td>
                                <td>
                                    <button type="button" class="layui-btn layui-btn-xs layui-btn-primary">{{$versions['phpVersion']??''}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>MySQL版本</td>
                                <td>
                                    <button type="button" class="layui-btn layui-btn-xs layui-btn-primary">{{$versions['mysqlVersion']??''}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Layui版本</td>
                                <td>
                                    <button type="button" class="layui-btn layui-btn-xs layui-btn-primary" id="layui-version">-</button>
                                </td>
                            </tr>
                            <tr>
                                <td>主要特色</td>
                                <td>
                                    <span class="layui-btn layui-btn-xs layui-btn-primary layui-border">响应式</span>
                                    <span class="layui-btn layui-btn-xs layui-btn-primary layui-border">清爽</span>
                                    <span class="layui-btn layui-btn-xs layui-btn-primary layui-border">极简</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layout.foot')
