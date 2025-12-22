@include('admin.layout.head')
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">
        @foreach($format as $val)
            @php
                $content = '';
                $sort = $val['sort'] ?? 0; // 默认排序值
                foreach ($row['getFormat'] as $v) {
                    if ($v['format_id'] == $val['id']) {
                        $content = $v['content'];
                        $sort = $v['sort'] ?? $sort;
                        break;
                    }
                }
            @endphp

            <div class="layui-form-item">
                <label class="layui-form-label">{{$val['title']}}</label>
                <div class="layui-input-block">
                    <textarea name="format[{{$val['id']}}][content]" class="layui-textarea">{{$content}}</textarea>
                </div>
                <label class="layui-form-label">排序</label>
                <div class="layui-input-block" style="width:150px;">
                    <input type="number" name="format[{{$val['id']}}][sort]" value="{{$sort}}" class="layui-input">
                </div>
            </div>
        @endforeach

        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>
    </form>
</div>
@include('admin.layout.foot')
