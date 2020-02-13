var Task = function () {

    var handleList = function () {
        var dataArr = {};
        var columnWidth = {"width": "10%", "targets": 0};

        var arrList = {
            'tableID': '#empTaskTable',
            'ajaxURL': baseurl + "employee/emp-task-ajaxAction",
            'ajaxAction': 'getdatatable',
            'postData': dataArr,
            'hideColumnList': [],
            'noSearchApply': [0],
            'noSortingApply': [3],
            'defaultSortColumn': 0,
            'defaultSortOrder': 'desc',
            'setColumnWidth': columnWidth
        };
        getDataTable(arrList);
    }

    $('body').on('click', '.taskDetailsModel', function () {
        var data = $(this).attr('data-id');

        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            },
            url: baseurl + "employee/emp-task-ajaxAction",
            data: {'action': 'getTaskDetails', 'data': data},
            success: function (data) {
                var output = JSON.parse(data);
                console.log(output);
                $('.task').html(output.task);
                $('.about_task').html(output.about_task);
                if(output.file!=null)
                {
                    $('.dwnltaskfileBtn').show();
                    $('.dwnltaskfile').attr('href', baseurl +"uploads/tasks/"+  output.file);
                }else{
                    $('.dwnltaskfileBtn').hide();
                }

            }
        });
    });
    $('body').on('click', '.updateTaskModel', function () {
        var data = $(this).attr('data-id');
       
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val(),
            },
            url: baseurl + "employee/emp-task-ajaxAction",
            data: {'action': 'getTaskDetails', 'data': data},
            success: function (data) {
                var output = JSON.parse(data);
                console.log(output);
                $('.task_id').val(output.id);
                $('.complete_progress').val(output.complete_progress);
                $('.task_status').val(output.task_status);
                $('.location').val(output.location);
                if(output.emp_updated_file!=null)
                {
                    $('.fileName').show();
                    $('.fileName').attr('href',baseurl +'/uploads/tasks/'+output.emp_updated_file);
                }else{
                    // $('.fileName').attr('href',baseurl +'/uploads/tasks/'+output.emp_updated_file);
                    $('.fileName').hide();
                }
            }
        });
    });


    var updateTask = function () {
        var form = $('#updateTask');
        var rules = {
            complete_progress: {required: true,number:true},
            task_status: {required: true},
            location: {required: true},

        };
        handleFormValidate(form, rules, function (form) {
            handleAjaxFormSubmit(form, true);
        });


    };
    
    var commentlist = function(){
        var form = $('#addEmpTaskComment');
            var rules = {
                comments: {required: true}
            };
            handleFormValidate(form, rules, function(form) {
                 ajaxcall($(form).attr('action'), $(form).serialize(), function (output) {
                    handleAjaxResponse(output);
                });
            });
    };
    return {
        init: function () {
            handleList();
            updateTask();
        },
        comments:function(){
            commentlist();
        },
    }
}();