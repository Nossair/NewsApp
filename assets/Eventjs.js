

jQuery(document).ready(function($) {

    $("#datepicker").datepicker();
    $("#datepickerend").datepicker();
    $("#datepickerend").on('change',function () {
        $("#event_date_end_vote").val($("#datepickerend").val())
    })
    $
    var $list = $('#event_optionDateEvent');
    $list.on('dblclick','option',function (e){
        $(this).remove();
    })
    $('#buttontest').on('click',function (){
        var $date = $('#datepicker').val();
        $('#datepicker').val("");
        $list.append("<option selected >"+$date+"</option>")

    })

    $('#addEvent').on('click',function (e){
        $('#formEvent').validate()

        $.ajax({
            type: "POST",
            url: "/event/new",
            data: {
                name:$('#inputName').val(),
                description:$('#inputDescription').val(),
                groupMails:JSON.stringify($('#inputgroupMails').val()),
                categoryEvent:$('#inputCategoryEvent').val(),
                optionDateEvent:JSON.stringify($list.val()) ,
                dateEndVote:$('#endvote').val()

            },
            dataType:"json",
            success: function (response){
                window.location.href = '/event/'
            }

        });
    })

    $('#updateEvent').on('click',function (e){
        $('#formEvent').validate()

        $.ajax({
            type: "POST",
            url: window.location.pathname,
            data: {
                name:$('#inputName').val(),
                description:$('#inputDescription').val(),
                groupMails:JSON.stringify($('#inputgroupMails').val()),
                categoryEvent:$('#inputCategoryEvent').val(),
                optionDateEvent:JSON.stringify($list.val()) ,
                dateEndVote:$('#endvote').val()

            },
            dataType:"json",
            success: function (response){
                window.location.href = '/event/'
            }

        });
    })
})


