/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import axios from "axios";

jQuery(document).ready(function($) {

    $("#datepicker").datepicker();
    $("#datepickerend").datepicker();
    $("#datepickerend").on('change',function () {
       $("#event_date_end_vote").val($("#datepickerend").val())
    })
    $
    var $list = $('#event_optionDateEvent');

    $('#buttontest').on('click',function (){
        var $date = $('#datepicker').val();
        $('#datepicker').val("");
       $list.append("<option selected>"+$date+"</option>")

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




})


