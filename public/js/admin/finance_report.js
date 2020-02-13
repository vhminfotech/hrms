var FinanceReport = function() {
   
    var handleList=function(){
        
        $('body').on('click', '.financeReportDelete', function() {
            // $('#deleteModel').modal('show');
            var id = $(this).data('id');
            setTimeout(function() {
                $('.yes-sure:visible').attr('data-id', id);
            }, 500);
        })

        $('body').on('click', '.yes-sure', function() {
            var id = $(this).attr('data-id');
            var data = {id: id, _token: $('#_token').val()};
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url: baseurl + "admin/finance-report-ajaxAction",
                data: {'action': 'deleteFinanceReport', 'data': data},
                success: function(data) {
                    $('#deleteModel').modal('hide');
                    document.location.reload()
                    // handleAjaxResponse(data);
                }
            });
        });
        
       var dataArr = {};
        var columnWidth = {"width": "10%", "targets": 0};

        var arrList = {
            'tableID': '#dataTables-financeReport',
            'ajaxURL': baseurl + "admin/finance-report-ajaxAction",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'noSearchApply': [],
            'noSortingApply': [],
            'defaultSortColumn': 0,
            'defaultSortOrder': 'desc',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);
        
    }
    return {
        init: function() {
            handleList();
        },
        add:function(){
            addRole();
        },
        edit:function(){
            editRole();
        },
    }
}();