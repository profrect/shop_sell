define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'wallet.address/index',
        add_url: 'wallet.address/add',
        edit_url: 'wallet.address/edit',
        delete_url: 'wallet.address/delete',
        export_url: 'wallet.address/export',
        modify_url: 'wallet.address/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'currency_type', title: '币种类型'},                    {field: 'protocol_type', title: '协议类型'},                    {field: 'address', title: '地址'},                    {field: 'create_time', title: '创建时间'},                    {width: 250, title: '操作', templet: ea.table.tool},
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});