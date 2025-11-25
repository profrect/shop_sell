@include('admin.layout.head')
<div class="layuimini-container">
    <div class="layuimini-main">
        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="{{auths('wallet.address/add')}}"
               data-auth-edit="{{auths('wallet.address/edit')}}"
               data-auth-delete="{{auths('wallet.address/delete')}}"
               lay-filter="currentTable">
        </table>
    </div>
</div>
@include('admin.layout.foot')
