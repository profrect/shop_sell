@include('admin.layout.head')
<div class="layuimini-container">
    <div class="layuimini-main">
        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="{{auths('goods.format/add')}}"
               data-auth-edit="{{auths('goods.format/edit')}}"
               data-auth-delete="{{auths('goods.format/delete')}}"
               lay-filter="currentTable">
        </table>
    </div>
</div>
@include('admin.layout.foot')
