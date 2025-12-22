define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'goods.format/index',
        add_url: 'goods.format/add',
        edit_url: 'goods.format/edit',
        delete_url: 'goods.format/delete',
        export_url: 'goods.format/export',
        modify_url: 'goods.format/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'title', title: '标题'},
                    {field: 'sort', title: '排序', edit: 'text',width: 100},
                    {field: 'create_time', title: '创建时间'},
                    {width: 250, title: '操作', templet: ea.table.tool},
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
