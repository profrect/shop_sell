<form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input type="text" name="set_title" class="layui-input" lay-verify="required" placeholder="请输入文字标题" value="{{sysconfig('mall_set','set_title')}}">
            <tip>请输入文字标题。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">大字文案</label>
        <div class="layui-input-block">
            <textarea type="text" name="set_big_desc" class="layui-textarea" lay-verify="required">{{sysconfig('mall_set','set_big_desc')}}</textarea>
            <tip>请输入大字文案。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">小字文案</label>
        <div class="layui-input-block">
            <textarea type="text" name="set_small_desc" class="layui-textarea" lay-verify="required">{{sysconfig('mall_set','set_small_desc')}}</textarea>
            <tip>请输入小字文案。</tip>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">立刻购买Url</label>
        <div class="layui-input-block">
            <input type="text" name="set_buy_now" class="layui-input" lay-verify="required" placeholder="立刻购买的跳转url" value="{{sysconfig('mall_set','set_buy_now')}}">
            <br>
            <tip>立刻购买的跳转url。</tip>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
