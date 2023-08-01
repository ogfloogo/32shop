define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index' + location.search,
                    // add_url: 'order/add',
                    // edit_url: 'order/edit',
                    // del_url: 'order/del',
                    multi_url: 'order/multi',
                    import_url: 'order/import',
                    table: 'order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                // pk: 'id',
                sortName: 'createtime',
                columns: [
                    [
                        // {checkbox: true},
                        // {field: 'id', title: __('Id')},
                        {field: 'order_sn', title: __('Order_sn'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'shop.name', title: __('Shop_id')},
                        {field: 'goods.name', title: __('商品')},
                        {field: 'num', title: __('Num')},
                        {field: 'amount', title: __('Amount'), operate:'BETWEEN'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
