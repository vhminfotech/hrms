var Communication = function () {

    var send_sms = function () {
        $('body').on('click', '.deleteMail', function() {
            var id = $(this).data('id');
            setTimeout(function() {
                $('.yes-sure-deletmail:visible').attr('data-id', id);
            }, 500);
        })

        $('body').on('click', '.yes-sure-deletmail', function() {
            var id = $(this).attr('data-id');
            var data = {id: id, _token: $('#_token').val()};
           
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                },
                url: baseurl + "company/trashMail",
                data: {'data': data},
                success: function(data) {
                    handleAjaxResponse(data);
                }
            });
        });

//        $('.chat-user').on("click", function () {
//            console.log($(this).attr("data-id"));
//        });

        var form = $('#new_communication');
        var rules = {
            emp_id: {required: true},
            subject: {required: true},
            message: {required: true}
        };

        handleFormValidate(form, rules, function (form) {
            handleAjaxFormSubmit(form, true);
        });

        /*$('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });*/
    }

    var compose_mail_func = function(){
        $('.summernote').summernote({
            height: '250px',
            placeholder: 'Enter your message here....'
        });

        $('.chat-user').on("click", function () {
            console.log($(this).attr("data-id"));
        });

        var form = $('#new_communication');
        var rules = {
            emp_id: {required: true},
            subject: {required: true},
            message: {required: true}
        };

        handleFormValidate(form, rules, function (form) {
            handleAjaxFormSubmit(form, true);
        });
    }
    
    var trash_mail = function(){
        
    }
    
    var forward_mail = function(){
        $('.summernote').summernote({
            height: '250px',
            placeholder: 'Enter your message here....'
        });
        
        var form = $('#forwardFrom');
        var rules = {
            emp_id: {required: true},
            subject: {required: true},
            message: {required: true}
        };

        handleFormValidate(form, rules, function (form) {
            handleAjaxFormSubmit(form, true);
        });
    }

    return {
        init: function () {
            send_sms();
        },
        compose_mail:function(){
            compose_mail_func();
        },
        trash:function(){
           trash_mail(); 
        },
        forward:function(){
           forward_mail(); 
        },
    }
}();