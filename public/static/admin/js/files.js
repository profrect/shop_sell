define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'files/index',
        add_url: 'files/add',
        edit_url: 'files/edit',
        delete_url: 'files/delete',
        export_url: 'files/export',
        modify_url: 'files/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},                    {field: 'id', title: 'id'},                    {field: 'type', title: '图片类型'},                    {field: 'title', title: '标题'},                    {field: 'url', title: '地址'},                    {field: 'created_time', title: 'created_time'},                    {field: 'updated_time', title: 'updated_time'},                    {width: 250, title: '操作', templet: ea.table.tool},
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