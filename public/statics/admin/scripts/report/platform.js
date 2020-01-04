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
        "dom": 'BtHF',
        'searchDelay': 300, // 搜索延时
        'search': {
            regex : true // 是否开启模糊搜索
        },
        "order": [
            [0, "desc"]
        ],
        "searchCols": [
            { "search": $("#date").val() },
            null,
            null,// { "search": $(".btn_switch.active").data('type') },
            { "search": $("#last_date").val() },
            { "search": $("#statistics").prop('checked') },
        ],
        'buttons': [
            {
                extend: 'copy',
                className: 'btn-primary',
                text: '复制'
            }, {
                extend: 'print',
                className: 'btn-primary',
                text: '打印',
                title: '电商报表'
            }, {
                extend: 'excel',
                className: 'btn-primary',
                text: '导出为 Excel',
                title: '电商报表'
            // }, {
            //     extend: 'csv',
            //     title: '电商报表'
            // }, {
            //     extend: 'pdfHtml5',
            //     title: '电商报表'
                // messageTop: 'PDF created by PDFMake with Buttons for DataTables.', // no use
                // messageBottom: 'messageBottom', // no use
                // customize: function ( doc ) {
                //     doc.content.splice( 1, 0, {
                //         margin: [ 0, 0, 0, 12 ],
                //         alignment: 'center',
                //         image: ''
                //     });
                // }
                // footer: true,
                // download: 'open'
            }
        ],

        "pagingType": "full_numbers", // 分页样式 full_numbers 首页 末页 上页 下页
        'aLengthMenu': [50, 20, 100],
        "columns": [
            {"data": "date", "name": "date", "orderable": true},
            {"data": "corporation_title", "name": "corporation_title", "orderable": false},
            {"data": "type_name", "name": "type_name", "orderable": false},
            {"data": "consumption", "name": "consumption", "orderable": true},
            {"data": "consumption_real", "name": "consumption_real", "orderable": true},
            {"data": "dialog_useful", "name": "dialog_useful", "orderable": true},
            {"data": "dialog_useful_cost", "name": "dialog_useful_cost", "orderable": true},
            {"data": "bespeak", "name": "bespeak", "orderable": true},
            {"data": "bespeak_cost", "name": "bespeak_cost", "orderable": true},
            {"data": "visit", "name": "visit", "orderable": true},
            {"data": "visit_cost", "name": "visit_cost", "orderable": true},
            {"data": "achievement", "name": "achievement", "orderable": true},
            {"data": "achievement_first", "name": "achievement_first", "orderable": true},
            {"data": "achievement_other", "name": "achievement_other", "orderable": true},
            // {"data": "dialog_useless", "name": "dialog_useless", "orderable": true},
            // {"data": "dialog_useless_percent", "name": "dialog_useless_percent", "orderable": true},
        ]
    });

    $('select[name=type]').change(function(){ // 类型
        var type = $(this).val();
        table.column(2).search(type).draw();
    });
    $('select[name=corporation_id]').change(function(){ // 公司
        var corporation_id = $(this).val();
        table.column(1).search(corporation_id).draw();
    });
    $('#statistics').change(function(){
        var checked = $(this).prop('checked');
        table.column(5).search(checked).draw();
    });

    var date = $('#date').datetimepicker({
        locale: 'zh-CN',
        format: 'YYYY-MM-DD',
        showClear: true,
        viewMode: 'days',
    }).on('dp.change', function(event){
        var stringdate = event.date.format('YYYY-MM-DD');

        table.column(0).search(stringdate).draw();
    });
    var last_date = $('#last_date').datetimepicker({
        locale: 'zh-CN',
        format: 'YYYY-MM-DD',
        showClear: true,
        viewMode: 'days',
    }).on('dp.change', function(event){
        table.column(4).search(event.date.format('YYYY-MM-DD')).draw();
    });
});