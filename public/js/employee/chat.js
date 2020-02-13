var Chat = function () {

    var handleList = function () {
        $('.chat-discussion').scrollTop($('.chat-discussion')[0].scrollHeight);
        
        var to_user_id = $('#to_user_id').val();
        fetch_user();
        setInterval(function(){
//            fetch_user();
            autorefresh();
        }, 2000);
        
        function autorefresh(){
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'autorefresh'},
                success: function(data) {
                    var output = JSON.parse(data);
                    var data= output.chatdetails;
                    var page_no = 0;
                    if(data == 'false'){
                        
                    }else{
                        $('#to_user_id').val(output.reciverid);
                    if(page_no==1)
                    {
                        $('.user-message-list').empty();
                    }
                    
                    page_no++;
                    $('#page_no').val(page_no);
                    if(data){
                            $('.user-message-list').html('');
                            for(var i = 0; i< data.length; i++){
                               
                                if(data[i].user_image == "" || data[i].user_image == null){
                                     var userimg="user.png";
    //                                var userimg=baseurl+"uploads/client/user.jpg";
                                }else{
                                    var userimg=data[i].user_image;
                                }
                                if(data[i].to_user_id!=output.reciverid)
                                {
                                    $('.user-message-list').append("<div class='chat-message left'>\
                                                <img class='message-avatar' src='"+baseurl+"uploads/client/"+userimg+"' alt='"+data[i].name+"' >\
                                                <div class='message'>\
                                                    <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                    <span class='message-date'>"+data[i].created_at+"</span>\
                                                    <span class='message-content'>"+data[i].chat_message+"</span>\
                                                </div></div>");
                                }else{
                                    $('.user-message-list').append("<div class='chat-message right'>\
                                                <img class='message-avatar' src='"+baseurl+"uploads/client/"+userimg+"' alt='"+data[i].name+"' >\
                                                <div class='message'>\
                                                    <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                    <span class='message-date'>"+data[i].created_at+"</span>\
                                                    <span class='message-content'>"+data[i].chat_message+"</span>\
                                                </div></div>");
                                }
                            }

                        }
                    }
                }
            });
        }
        
        function fetch_user() {
            $('.chat-discussion').scrollTop($('.chat-discussion')[0].scrollHeight);
            $('.users-list').html('');
            
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'fetch_user'},
                success: function(data) {
                    if(data){
                        for(var i = 0; i< data.length; i++){
                            var userimg = '';
                            if(data[i].user_image == "" || data[i].user_image == null){
                                 userimg=baseurl+"uploads/client/user.png";
                            }else{
                                userimg=baseurl+"uploads/client/"+data[i].user_image;
                            }
                            $('.users-list').append("<a data-id='"+data[i].id+"' data-user-name='"+data[i].name+"' class='user-message' href='javascript:void(0);'><div class='chat-user'><img class='chat-avatar' src='"+userimg+"' alt=''><div class='chat-user-name'>"+data[i].name+"</div></div></a>");
                        }
                    }
                },
                error: function(err) {
                }
            });
        }

        $("#search_user").keyup(function(){
            var search_name=$('#search_user').val();
//            console.log(search_name);
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: "chat-ajaxAction",
                data: {'action': 'search_user_list','search_name':search_name},
                success: function(data) {
                    if(data){
                        $('.users-list').empty();
                        for(i = 0; i< data.length; i++){
                            if(data[i].user_image == null || data[i].user_image == ''){
                                var userimg=baseurl+"uploads/client/user.png";
                            }else{
                                var userimg=baseurl+"uploads/client/" +data[i].user_image;
//                                var userimg=baseurl+"uploads/client/user.jpg";
                            }

                            $('.users-list').append("<div class='chat-user'><img class='chat-avatar' src='"+userimg+"' alt=''><div class='chat-user-name'><a data-id='"+data[i].id+"' data-user-name='"+data[i].name+"' class='user-message' href='javascript:void(0);'  >"+data[i].name+"</a></div></div>");
                        }
                        
                    }
                },
                error: function(err) {
                    //alert("error"+JSON.stringify(err));
                }
            });
        });

        $('body').on('click', '.user-message', function () {
            
            $('.chat-discussion').scrollTop($('.chat-discussion')[0].scrollHeight);
            
            $("#message").val('');
            $("#message").text('');
            var to_user_id = $(this).attr('data-id');
            var to_user_name = $(this).attr('data-user-name');
            
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'setuserid','to_user_id':to_user_id,'to_user_name':to_user_name},
                success: function(data) {
                    
                },
            });
            
            $('#to_user_name').html(to_user_name);
            $('#to_user_id').val(to_user_id);
            var page=1;
            chetuserlist(to_user_id,page);
        });

        $('.send_chat').on("click", function () {
           var to_user_id = $('#to_user_id').val();
            var message = $('#message').val();
            
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: "chat-ajaxAction",
                data: {'action': 'insert_chat', 'to_user_id':to_user_id, 'message':message},
                success: function(data) {
                    $('#message').val("");
                    if(data.chat_message!="")
                    {
                        var userimg ='';
                        if(data.user_image == "" || data.user_image == null){
                            userimg="user.png";
                        }else{
                            userimg=data.user_image;
                        }
                        console.log(data.created_at);
                        $('.user-message-list').append("<div class='chat-message right'>\
                        <img class='message-avatar' src='"+baseurl+"uploads/client/"+userimg+"' alt='"+data.name+"' >\
                        <div class='message'>\
                            <a class='message-author' href='#'>"+data.name+"</a>\
                            <span class='message-date'>"+data.created_at+"</span>\
                            <span class='message-content'>"+data.chat_message+"</span>\
                        </div></div>");
                    }
                }
            });
        });
        
        $('.user-message-list').scroll(function() {
            var scroll = $('.user-message-list').scrollTop();

            if (scroll == 0) {
                var to_user_id = $('#to_user_id').val();
                var page_no = $('#page_no').val();
                chetuserlist(to_user_id,page_no);
            }
        });

        
        function chetuserlist(to_user_id,page_no) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: "chat-ajaxAction",
                data: {'action': 'user-message-list','to_user_id':to_user_id,'page_no':page_no},
                success: function(data) {
                    $('#to_user_id').val(to_user_id);
                    if(page_no==1)
                    {
                        $('.user-message-list').empty();
                    }
                    
                    page_no++;
                    $('#page_no').val(page_no);
                    if(data){
                        for(i = 0; i< data.length; i++){
                             var userimg ='';
                            if(data[i].user_image == "" || data[i].user_image == null){
                                userimg="user.png";
                            }else{
                                userimg=data[i].user_image;
                            }
                            if(data[i].to_user_id!=to_user_id)
                            {
                                $('.user-message-list').append("<div class='chat-message left'>\
                                            <img class='message-avatar' src='"+baseurl+"uploads/client/"+userimg+"' alt='"+data[i].name+"' >\
                                            <div class='message'>\
                                                <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                <span class='message-date'>"+data[i].created_at+"</span>\
                                                <span class='message-content'>"+data[i].chat_message+"</span>\
                                            </div></div>");
                            }else{
                                $('.user-message-list').append("<div class='chat-message right'>\
                                            <img class='message-avatar' src='"+baseurl+"uploads/client/"+userimg+"' alt='"+data[i].name+"' >\
                                            <div class='message'>\
                                                <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                <span class='message-date'>"+data[i].created_at+"</span>\
                                                <span class='message-content'>"+data[i].chat_message+"</span>\
                                            </div></div>");
                            }
                        }
                        
                    }
                },
                error: function(err) {
                    //alert("error"+JSON.stringify(err));
                }
            });
        }
    }
//    Employee
    var initdefultopen = function (userid) {
        
            var to_user_id = $('#to_user_id').val();
        $('.chat-discussion').scrollTop($('.chat-discussion')[0].scrollHeight);
        setInterval(function(){
//            fetch_user();
            autorefresh();
        }, 2000);
        
        function autorefresh(){
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'autorefresh'},
                success: function(data) {
                    var output = JSON.parse(data);
                    var data= output.chatdetails;
                    var page_no = 0;
                    if(data == 'false'){
                        
                    }else{
                        $('#to_user_id').val(output.reciverid);
                    if(page_no==1)
                    {
                        $('.user-message-list').empty();
                    }
                    
                    page_no++;
                    $('#page_no').val(page_no);
                    if(data){
                            $('.user-message-list').html('');
                            for(var i = 0; i< data.length; i++){
                               
                                if(data[i].user_image == "" || data[i].user_image == null){
                                     var userimg="user.png";
    //                                var userimg=baseurl+"uploads/client/user.jpg";
                                }else{
                                    var userimg=data[i].user_image;
                                }
                                if(data[i].to_user_id!=output.reciverid)
                                {
                                    $('.user-message-list').append("<div class='chat-message left'>\
                                                <img class='message-avatar' src='"+baseurl+"uploads/client/"+userimg+"' alt='"+data[i].name+"' >\
                                                <div class='message'>\
                                                    <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                    <span class='message-date'>"+data[i].created_at+"</span>\
                                                    <span class='message-content'>"+data[i].chat_message+"</span>\
                                                </div></div>");
                                }else{
                                    $('.user-message-list').append("<div class='chat-message right'>\
                                                <img class='message-avatar' src='"+baseurl+"uploads/client/"+userimg+"' alt='"+data[i].name+"' >\
                                                <div class='message'>\
                                                    <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                    <span class='message-date'>"+data[i].created_at+"</span>\
                                                    <span class='message-content'>"+data[i].chat_message+"</span>\
                                                </div></div>");
                                }
                            }

                        }
                    }
                }
            });
        }
        fetch_user(userid);
        function fetch_user(userid) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'fetch_user'},
                success: function(data) {
                    
                    if(data){
                        for(i = 0; i< data.length; i++){

                            if(data[i].user_image!=""){
                                // var userimg=baseurl+"uploads/client/"+data[i].user_image;
                                var userimg=baseurl+"uploads/client/user.jpg";
                            }else{
                                var userimg=baseurl+"uploads/client/user.jpg";
                            }

                            $('.users-list').append("<a data-id='"+data[i].id+"' data-user-name='"+data[i].name+"' class='user-message' href='javascript:void(0);'  ><div class='chat-user'><img class='chat-avatar' src='"+userimg+"' alt=''><div class='chat-user-name'>"+data[i].name+"</div></div></a>");
                        }
                        var page = 1;
                        chetuserlist(userid,page);
                    }
                },
                error: function(err) {
                    //alert("error"+JSON.stringify(err));
                }
            });
        }

        $("#search_user").keyup(function(){
            var search_name=$('#search_user').val();
//            console.log(search_name);
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'search_user_list','search_name':search_name},
                success: function(data) {
                    if(data){
                        $('.users-list').empty();
                        for(i = 0; i< data.length; i++){
                            if(data[i].user_image!=""){
                            // var userimg=baseurl+"uploads/client/"+data[i].user_image;
                            var userimg=baseurl+"uploads/client/user.jpg";
                            }else{
                                var userimg=baseurl+"uploads/client/user.jpg";
                            }

                            $('.users-list').append("<div class='chat-user'><img class='chat-avatar' src='"+userimg+"' alt=''><div class='chat-user-name'><a data-id='"+data[i].id+"' data-user-name='"+data[i].name+"' class='user-message' href='javascript:void(0);'  >"+data[i].name+"</a></div></div>");
                        }
                        
                    }
                },
                error: function(err) {
                    //alert("error"+JSON.stringify(err));
                }
            });
        });

        $('body').on('click', '.user-message', function () {
//            
            $('.chat-discussion').scrollTop($('.chat-discussion')[0].scrollHeight);
            var to_user_id = $(this).attr('data-id');
            var to_user_name = $(this).attr('data-user-name');
            $('#to_user_name').html(to_user_name);
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'setuserid','to_user_id':to_user_id,'to_user_name':to_user_name},
                success: function(data) {
                    window.location.replace(baseurl + "employee/employee-chat");
                },
            });
            
            window.location.replace(baseurl + "employee/employee-chat");
        });

        
        $('body').on('click', '.send_chat', function () {    
//            var to_user_id = $('#to_user_id').val();
            var message = $('#message').val();
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'insert_chat', 'to_user_id':to_user_id, 'message':message},
                success: function(data) {
                    $('#message').val("");
                    if(data.chat_message!="")
                    {
                        console.log(data.created_at);
                        $('.user-message-list').append("<div class='chat-message right'>\
                        <img class='message-avatar' src='"+baseurl+"uploads/client/"+data.user_image+"' alt='"+data.name+"' >\
                        <div class='message'>\
                            <a class='message-author' href='#'>"+data.name+"</a>\
                            <span class='message-date'>"+data.created_at+"</span>\
                            <span class='message-content'>"+data.chat_message+"</span>\
                        </div></div>");
                    }
                }
            });
        });
        
        $('.user-message-list').scroll(function() {
            var scroll = $('.user-message-list').scrollTop();

            if (scroll == 0) {
                var to_user_id = $('#to_user_id').val();
                var page_no = $('#page_no').val();
                chetuserlist(to_user_id,page_no);
            }
        });

        
        function chetuserlist(to_user_id,page_no) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: baseurl+"employee/chat-ajaxAction",
                data: {'action': 'user-message-list','to_user_id':to_user_id,'page_no':page_no},
                success: function(data) {
                    $('#to_user_id').val(to_user_id);
                    if(page_no==1)
                    {
                        $('.user-message-list').empty();
                    }
                    console.log(page_no);
                    page_no++;
                    $('#page_no').val(page_no);
                    if(data){
                        for(i = 0; i< data.length; i++){

                            if(data[i].to_user_id!=to_user_id)
                            {
                                $('.user-message-list').prepend("<div class='chat-message left'>\
                                            <img class='message-avatar' src='"+baseurl+"uploads/client/"+data[i].user_image+"' alt='"+data[i].name+"' >\
                                            <div class='message'>\
                                                <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                <span class='message-date'>"+data[i].created_at+"</span>\
                                                <span class='message-content'>"+data[i].chat_message+"</span>\
                                            </div></div>");
                            }else{
                                $('.user-message-list').prepend("<div class='chat-message right'>\
                                            <img class='message-avatar' src='"+baseurl+"uploads/client/"+data[i].user_image+"' alt='"+data[i].name+"' >\
                                            <div class='message'>\
                                                <a class='message-author' href='#'>"+data[i].name+"</a>\
                                                <span class='message-date'>"+data[i].created_at+"</span>\
                                                <span class='message-content'>"+data[i].chat_message+"</span>\
                                            </div></div>");
                            }
                        }
                        
                    }
                },
                error: function(err) {
                    //alert("error"+JSON.stringify(err));
                }
            });
        } 


    }
    

    return {
        init: function () {
            handleList();
        },
        initdefultopen: function (userid) {
            initdefultopen(userid);
        },
        
    }
}();


