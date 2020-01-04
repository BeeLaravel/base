$(document).ready(function(){
    var lang = {
        "sProcessing": "处理中...",
        "sLengthMenu": "每页 _MENU_ 项",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "当前显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项。",
        "sInfoEmpty": "当前显示第 0 至 0 项，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "没有查到数据",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页",
            "sJump": "跳转"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    };
    var table = $('#datatable').DataTable({
        "processing": true,
        'language': lang,

        "serverSide": true,
        "ajax": {
            'url': url
        },

        'searchDelay': 300, // 搜索延时
        'search': {
            regex : true // 是否开启模糊搜索
        },
        "order": [
            [0, "desc"]
        ],

        "pagingType": "full_numbers", // 分页样式 full_numbers 首页 末页 上页 下页
        'aLengthMenu': [20, 50, 100],
        "columns": [
            {"data": "id", "name": "id"},
            {"data": "slug", "name": "slug", "orderable": true},
            {"data": "title", "name": "title", "orderable": true},
            {"data": "created_at", "name": "created_at", "orderable": true},
            {"data": "updated_at", "name": "updated_at", "orderable": true},
            {"data": "button", "name": "button", 'type': 'html', "orderable": false}
        ]
    });
    // $('select[name=type]').change(function(){ // 类型
    //     var type = $(this).val();
    //     table.column(2).search(type).draw();
    // });
});