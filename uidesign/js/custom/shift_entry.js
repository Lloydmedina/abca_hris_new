let base_url = window.location.origin + '/' + window.location.pathname.split('/')[1] + '/';
if(window.location.origin != "http://localhost"){
    base_url = window.location.origin + '/';
}

function offBeforeunload() {
    $(window).off('beforeunload');
}

function onBeforeunload() {
    $(window).on('beforeunload', function () {
        return confirm('Data you have entered may not be saved!');
    });
}


$(document).ready(function(){

    // delete Leave

    $(document).on('click', '.deleteShift', function(e) {
        
        let id = $(this).attr('id');
        let url = base_url + 'delete_shift';
        let btn_confirm = confirm("Delete Shift?");

        if (btn_confirm) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {id:id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if(result.code == 1){
                        $('#'+id).hide();
                    }
                },
                error: function(result){
                    console.log(result);
                }

            });
        }

    });

    // check all

    $('#checked_all').click(function(){
        if($(this).is(":checked")){
            $( ".checked").prop('checked', true);
        }
        else{
            $( ".checked").prop('checked', false);
        }
    })

    // Selected date validation
    $(document).on('click', '#sub_btn_save_leave', function(e) {

        let date_selected = $('#date_selected').val();
        let myDate = new Date(date_selected);
        let today = new Date();
        
        if ( myDate > today ) { 
            alert('You cannot enter a date in the future!');
        }
        else{
            // Trigger form submit button
            offBeforeunload();
            $('#btn_save_leave').click();
        }

    });

    $(document).on('change', '#date_selected_from, #date_selected_to', function(){

        let date_from = $('#date_selected_from').val();
        let date_to = $('#date_selected_to').val();

        if(date_to && date_from){
            
            $('#no_of_days').val(get_no_days(date_from, date_to));
        }

    });

});

function get_no_days(date_from, date_to){

    var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
    var firstDate = new Date(date_from);
    var secondDate = new Date(date_to);
    var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay))) + 1;
    var countSun = countSundays(firstDate, secondDate);

    return diffDays;
    // return diffDays;
}

function countSundays(startDate, endDate){
    var sundays = 0;
        for (var i = startDate; i <= endDate; i.setDate(i.getDate()+1)){
            if (i.getDay() == 0) sundays++;
        }
    return sundays;
}

function get_diff() {
    var number_of_hours = 0;
    
    var start ="00:00";
    var end ="00:00";

    try {
       start = $('#time_from').val();
       end = $('#time_to').val();
    } catch(e) {

    }

    try {
       start = start.split(":");
        end = end.split(":");
        var startDate = new Date(0, 0, 0, start[0], start[1], 0);
        var endDate = new Date(0, 0, 0, end[0], end[1], 0);
        var diff = endDate.getTime() - startDate.getTime();
        var hours = Math.floor(diff / 1000 / 60 / 60);
        diff -= hours * 1000 * 60 * 60;
        var minutes = Math.floor(diff / 1000 / 60);
        // if(hours!=0){
        //   hours = hours - 1;
        // }
        
        minutes = minutes / 60;

        // If using time pickers with 24 hours format, add the below line get exact hours
        if (hours < 0){
           hours = hours + 24;
        }
       if (minutes==0) {
          number_of_hours = hours;
       }
       else
        number_of_hours = parseFloat( (hours + minutes) ).toFixed(2);
    } catch(e) {
       
    }
    if(isNaN(number_of_hours)){
        $('#total_hours').val(0);
    }else{
        $('#total_hours').val(number_of_hours);
    }
 }



 function DisplayEmployeeShift(emp_id,date_from,date_to){
    let url = base_url+'get_emp_shift';
    let str = "";
    let checkedIndentifier = '';
    $.ajax({
                url: url,
                type: 'GET',
                data: {emp_id : emp_id,date_from: date_from, date_to: date_to},
                success: function(result) {
                    $.each(result[0], function (i) {
                        checkedIndentifier = '';
                        if(result[0][i].status == 1) checkedIndentifier = 'checked';
                        
                        str += '<tr>\
                                   <td>'+result[0][i].shift_date+'</td>\
                                   <td><select id="u_shift_code[]" name="u_shift_code[]" class="form-control custom-select" value="'+ result[0][i].shift_code_id+'" required>';
                                    str +='<option value="" selected disabled>Select shift code</option>';
                        $.each(result[1], function (g) {
                            if(result[1][g].id == result[0][i].shift_code_id)
                            {
                                // console.log("yes");
                                str += '<option value="'+ result[1][g].id +'" selected>'+result[1][g].shift_code+'</option>';
                            }
                            else{
                                str += '<option value="'+ result[1][g].id +'">'+result[1][g].shift_code+'</option>';
                            }
                            // str += '<option value="'+ result[1][i].id +'">'+result[1][i].shift_code+'</option>';
                            
                        });
                                //    <td><input class="form-control" type="text" name="u_shift_code[]" value="'+result[0][i].shift_code+'"></td></td>
                        str += '</select><td><input type="number" name="u_break_hr[]" value="'+result[0][i].no_hr_break+'" any></td>\
                                   <td><input class="form-control" type="text" name="u_remarks[]" value="'+result[0][i].remarks+'"></td>\
                                   <td hidden><input type="text" name="u_shift_id[]" value="'+result[0][i].id+'"></td>\
                                   <td class="text-center"><div class="form-check"><input name="u_status['+result[0][i].id+']" style="width: 20px;height: 20px;" class="form-check-input" value="'+result[0][i].id+'" type="checkbox" '+checkedIndentifier+'></div></td>\
                               </tr>';
                    });
   
                   // set empty
                   $('#emp_shift_body').empty();
   
                   // append data
                   $('#emp_shift_table').append(str);
                },
                error: function() {
                    console.log('Oppss something went wrong');
                }
            });
 }



