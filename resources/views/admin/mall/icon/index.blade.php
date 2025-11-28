@include('admin.layout.head')
<div class="layuimini-container">
    <div class="layuimini-main">
        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="{{auths('icon/add')}}"
               data-auth-edit="{{auths('icon/edit')}}"
               data-auth-delete="{{auths('icon/delete')}}"
               lay-filter="currentTable">
        </table>
    </div>
</div>
@include('admin.layout.foot')
