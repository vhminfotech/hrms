var Advancesalaryrequest = function (){

    var handleList = function () {
        
         $('body').on('click', '.approve', function() {
            var id = $(this).data('id');
            setTimeout(function() {
                $('.yesapprove:visible').attr('data-id', id);
            }, 500);
        })

        $('body').on('click', '.yesapprove', function() {
            var id = $(this).attr('data-id');
            var data = {id: id, _token: $('#_token').val()};
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url: baseurl + "employee/employee-advance-salary-request-ajaxAction",
                data: {'action': 'approveRequest', 'data': data},
                success: function(data) {
                    handleAjaxResponse(data);
                }
            });
        });
        
        $('body').on('click', '.checkAl', function () {
            if (this.checked) {
                $('.chkChangeReq:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.chkChangeReq:checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        $("body").on('click', '.changeStatus', function () {
            $("#chkChangeReq").val('');
            var status = $(this).val();
            var arrEmp = [];
            $('.chkChangeReq:checkbox:checked').each(function () {
                // var invoiceNo = $(this).attr('id');
                var empId = $(this).val();
                arrEmp.push(empId);
                // arrInvoice.push(invoiceNo);
            });
            if (arrEmp.length > 0) {
                var data = {status: status,arrEmp: arrEmp, _token: $('#_token').val()};
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url: baseurl + "employee/employee-advance-salary-request-ajaxAction",
                data: {'action': 'changeSalaryStatus', 'data': data},
                success: function(data) {
                    handleAjaxResponse(data);
                }
            });
            } else {
                alert('Please Select at least one Record');
            }
        });
        $('body').on('change', '.empName', function() {
            var dept = $('option:selected', this).attr('data-dept');
            var comp = $('option:selected', this).attr('data-comp');
            var val = $('#empName option:selected').val();
            var empname = $('#empName option:selected').text();
            $('.emp_id').val(val);
            $('.cmp_id').val(comp);
            $('.dep_id').val(dept);
            $('.empname').val(empname);
        })   
         $('body').on('click', '.disapprove', function() {
            var id = $(this).data('id');
            
            setTimeout(function() {
                $('.yesreject:visible').attr('data-id', id);
            }, 500);
        })

        var form = $('#addNewRequest');
        var rules = {
            emp_name: {required: true},
            empName: {required: true},
            date_of_submit: {required: true},
            emp_id: {required: true},
            date_of_submit: {required: true},
            reason: {required: true},
            // files: {required: true},
            
        };
        handleFormValidate(form, rules, function (form) {
            handleAjaxFormSubmit(form, true);
        });
        $('body').on('click', '.yesreject', function() {
            var id = $(this).attr('data-id');
            
            var data = {id: id, _token: $('#_token').val()};
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url: baseurl + "employee/employee-advance-salary-request-ajaxAction",
                data: {'action': 'disapproveRequest', 'data': data},
                success: function(data) {
                    handleAjaxResponse(data);
                }
            });
        });
        
        var dataArr = {};
        var columnWidth = {"width": "10%", "targets": 0};
        var arrList = {
            'tableID': '#requestlist',
            'ajaxURL': baseurl + "employee/employee-advance-salary-request-ajaxAction",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'noSearchApply': [0],
            'noSortingApply': [0,3],
            'defaultSortColumn': 2,
            'defaultSortOrder': 'desc',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);
    };
    
    var addRequest = function () {
        
        var form = $('#addNewRequest');
        var rules = {
            emp_name: {required: true},
            emp_id: {required: true},
            date_of_submit: {required: true},
            reason: {required: true},
            files: {required: true},
            
        };
        handleFormValidate(form, rules, function (form) {
            handleAjaxFormSubmit(form, true);
        });
    };
    
    var editRequest=function(){
        var form = $('#editNewRequest');
        var rules = {
            emp_name: {required: true},
            emp_id: {required: true},
            date_of_submit: {required: true},
            reason: {required: true},
        };
        handleFormValidate(form, rules, function (form) {
            handleAjaxFormSubmit(form, true);
        });
    }

    var approvedReqList = function () {
        $('body').on('click','#selectall',function() {
            $('.approved_chk_id').not(this).prop('checked', this.checked);
        });
        var checkedBoxArr = [];
  
        $('body').on('click','#approved_chk_id',function(){
            var checked = $(this).val();
            if ($(this).is(':checked')) {
                checkedBoxArr.push(checked);
            } else {
                checkedBoxArr.splice($.inArray(checked, tmp), 1);
            }
        });

        $('body').on('click', '.applyBtn', function () {
            var month = $('#month option:selected').val();
            var year = $('#year option:selected').val();
            var querystring = (month == '' && typeof month === 'undefined') ? '&month=' : '&month=' + month;
            querystring += (year == '' && typeof year === 'undefined') ? '&year=' : '&year=' + year;
            location.href = baseurl + 'employee/employee-approved-advance-salary-request?' + querystring;
        }); 
         $('body').on('click', '.clearBtn', function () {
            location.href = baseurl + 'employee/employee-approved-advance-salary-request';
        });
        $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    // { extend: 'copy'},
                    // {extend: 'csv'},
                    // {extend: 'excel', title: 'ExampleFile'},
                    // {extend: 'pdf', title: 'ExampleFile'},

                    // {extend: 'print',
                    //  customize: function (win){
                    //         $(win.document.body).addClass('white-bg');
                    //         $(win.document.body).css('font-size', '10px');

                    //         $(win.document.body).find('table')
                    //                 .addClass('compact')
                    //                 .css('font-size', 'inherit');
                    // }
                    // }
                ]

            });
        $('#DownloadButton').on('click', function () {
            var token=$("#_token").val();
            var selecteditem = [];
            $.each($(".approved_chk_id:checked"), function(){            
                selecteditem.push($(this).val());
            });
            
            if(selecteditem.length == '0'){
                    showToster("error", "First Select any Employee", '');
            }else{
                $.ajax({
                    type: "post",
                    url: baseurl + "employee/employee-createApprovedPdf",
                    data: {"selecteditem":selecteditem,"_token":token},
                    success: function (response)
                    {
                       window.location.replace(baseurl + "employee/employee-downloadApprovedPdf?pdfname="+response);
                    }
                });
            }
        });

        $('#DownloadExcelButton').on('click', function () {
            var token=$("#_token").val();
            var selecteditem = [];
            $.each($(".approved_chk_id:checked"), function(){            
                selecteditem.push($(this).val());
            });
            
            if(selecteditem.length == '0'){
                    showToster("error", "First Select any Employee", '');
            }else{
                window.location.replace(baseurl + "employee/employee-createApprovedExcel?selecteditem="+selecteditem);
                // $.ajax({
                //     type: "post",
                //     url: baseurl + "employee/employee-createApprovedExcel",
                //     data: {"selecteditem":selecteditem,"_token":token},
                //     success: function (response)
                //     {
                //        window.location.replace(baseurl + "employee/employee-createApprovedExcel?pdfname="+response);
                //     }
                // });
            }
        });

        var dataArr = {};
        var columnWidth = {"width": "10%", "targets": 0};
        var arrList = {
            'tableID': '#approvedRequestlist',
            'ajaxURL': baseurl + "employee/employee-approved-salary-request-ajaxAction",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                }
            ],
            'select': {
              'style': 'multi'
            },
            'noSearchApply': [0],
            'noSortingApply': [3],
            'defaultSortColumn': 0,
            'defaultSortOrder': 'desc',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);
    };

    
      
    return {
        init: function () {
            handleList();
        },
        add: function(){
            addRequest();
        },
        edit:function(){
            editRequest();
        },
        initApprovedReqList:function(){
            approvedReqList();
        },
    }
}();