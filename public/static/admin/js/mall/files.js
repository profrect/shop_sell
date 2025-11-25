define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.files/index',
        add_url: 'mall.files/add',
        edit_url: 'mall.files/edit',
        delete_url: '',
        export_url: '',
        modify_url: '',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id', search: false},
                    {field: 'type_name', title: '图片类型', search: false},
                    {field: 'title', title: '标题', search: false},
                    {field: 'url', title: '地址', search: false, templet: ea.table.image},
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
