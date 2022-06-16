

jQuery(document).ready(function($) {



    $('#addGroupMail').on('click',function (e){


        $.ajax({
            type: "POST",
            url: "/group/mail/new",
            data: {
                name:$('#group_mail_name').val(),
                users:JSON.stringify($('#group_mail_users').val()) ,
            },
            dataType:"json",
            success: function (response){
                window.location.href = '/group/mail/'
            }

        });
    })
})


