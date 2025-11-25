@include('admin.layout.head')
<div class="layuimini-container">
    <div class="layuimini-main">
        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="{{auths('files/add')}}"
               data-auth-edit="{{auths('files/edit')}}"
               data-auth-delete="{{auths('files/delete')}}"
               lay-filter="currentTable">
        </table>
    </div>
</div>
@include('admin.layout.foot')
