$(function(){
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
        "aLengthMenu": [20, 50, 100],
        "columnDefs": [{
            "render": function(data, type, row) {
                return data + "<br>" + (row.phone ? row.phone : "(未设置)");
            },
            "targets": 1
        },{
            "render": function(data, type, row) {
                return data + "<br>" + (row.description ? row.description : "(暂没有描述)");
            },
            "targets": 2
        },{
            "render": function(data, type, row) {
                var return_data = data;
                if ( row.site_titles ) return_data += "<br><b>站点: </b>" + row.site_titles;
                if ( row.department_titles ) return_data += "<br><b>部门: </b>" + row.department_titles;
                return return_data;
            },
            "targets": 3
        },{
            "render": function(data, type, row) {
                return "<b>创建时间:</b> " + (row.created_at ? row.created_at : "(未记录)") + "<br>" + "<b>更新时间:</b> " + (data ? data : "(未记录)");
            },
            "targets": 4
        }],
        "columns": [
            {"data": "id", "name": "id"},
            {"data": "email", "name": "email", "orderable": true},
            {"data": "name", "name": "name", "orderable": true},
            // {"data": "description", "name": "description", "orderable": true},
            {"data": "corporation_title", "name": "corporation_title", "orderable": true},
            // {"data": "created_at", "name": "created_at", "orderable": true},
            {"data": "updated_at", "name": "updated_at", "orderable": true},
            {"data": "button", "name": "button", "type": "html", "orderable": false}
        ]
    });
});