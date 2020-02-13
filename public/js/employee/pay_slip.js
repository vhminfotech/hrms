var Paylip = function () {

    var handleList = function () {
        dateFormate('.date');

        $('body').on('change', '.checkAll', function () {
            if (this.checked) {
                $('.empId:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.empId:checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        $("body").on('click', '.downloadPdf', function () {
            $("#emparray").val('');
            var arrEmp = [];
            $('.empId:checkbox:checked').each(function () {
                var empId = $(this).val();
                arrEmp.push(empId);
            });
            if (arrEmp.length > 0) {
                $("#emparray").val(arrEmp);
                $('#paySlip').submit();
            } else {
                alert('Please Select at least one Record');
            }
        });
        
        $("body").on('click', '.review', function () {
            var data = [];
            var empid = $(this).attr('data-id');
            var month = $(this).attr('data-month');
            var year = $(this).attr('data-year');
            var data = {id: empid, month: month, year: year};
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url: baseurl + "employee/employee-payslip-ajaxAction",
                data: {'action': 'getempmodaldata', 'data': data},
                success: function (data) {
                    handleAjaxResponse(data);
                    var myJSON = JSON.parse(data);
                    var trHTML = '';
                    $.each(myJSON, function (i,val) {
                        trHTML += '<tr class="text-center"><td>' + val.empName+ '</td><td>' + val.company_name+ '</td><td>' + val.due_date+ '</td><td>' + val.remarks+ '</td><td>' + val.salary_grade+ '</td><td>' + val.over_time+ '</td></tr>';
                    });
                    $('.employeemodaldata').append(trHTML);
                }
            });


        });


        $("body").on('change', '.empSelectionType', function () {
            $('.checkAll').trigger('click');
        });

        $('body').on('click', '.applyBtn', function () {
            var consult_id = [];
            var department = $('#department option:selected').val();
            var month = $('#months option:selected').val();
            var year = $('#year option:selected').val();
            var employee = $('#employee option:selected').val();
            var querystring = (department == '' && typeof department === 'undefined') ? 'department=' : 'department=' + department;

            querystring += (month == '' && typeof month === 'undefined') ? '&month=' : '&month=' + month;
            querystring += (year == '' && typeof year === 'undefined') ? '&year=' : '&year=' + year;
            querystring += (employee == '' && typeof employee === 'undefined') ? '&employee=' : '&employee=' + employee;
            location.href = baseurl + 'employee/employee-pay-slip?' + querystring;

        });

        $('body').on('click', '.clearBtn', function () {
            location.href = baseurl + 'employee/employee-pay-slip';
        });

    }
    return {
        init: function () {
            handleList();
        }
    }

}();