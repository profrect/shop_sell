define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.icon/index',
        add_url: 'mall.icon/add',
        edit_url: 'mall.icon/edit',
        delete_url: 'mall.icon/delete',
        export_url: 'mall.icon/export',
        modify_url: 'mall.icon/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'icon', title: '图标', templet: ea.table.image,search: false},
                    {field: 'sort', title: '排序', edit: 'text',search: false},
                    {field: 'create_time', title: '创建时间',search: false},
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
