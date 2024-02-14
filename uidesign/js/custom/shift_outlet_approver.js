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

    $(document).on('click', '.approveLeave', function(e) {
        
        let id = $(this).attr('id');
        let url = base_url + 'approve_leave';
        let btn_confirm = confirm("Approve Leave?");

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

    $(document).on('click', '.rejectLeave', function(e) {
        
        let id = $(this).attr('id');
        let url = base_url + 'reject_leave';
        let btn_confirm = confirm("Reject Leave?");

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

    $('#selected_employee_form').submit(function(evt) {
        evt.preventDefault();
        let selected_employee_form = $(this).serialize();
        let url = base_url+'absenteeism';
        let str = '';
        let employee;
        onBeforeunload();
        //trigger btn close modal
        $('#btn_modal_close').click();

        //do ajax
        $.ajax({
            url: url,
            type: 'POST',
            data: selected_employee_form,
            success: function(result) {
                // result = JSON.parse(result);
                //console.log(result[0].Department_Empl);

                for (employee in result) {
                     str += '<tr>\
                                <td>'+result[employee].Name_Empl+'</td>\
                                <td>'+result[employee].Position_Empl+'</td>\
                                <td>'+result[employee].Department_Empl+'</td>\
                                <td><input class="form-control" type="text" name="remarks[]"></td>\
                                <td hidden><input type="text" name="employee_id[]" value="'+result[employee].SysPK_Empl+'"></td>\
                                <td hidden><input type="text" name="employee_number[]" value="'+result[employee].UserID_Empl+'"></td>\
                            </tr>';
                }

                // set empty
                $('#tbody_employee_selected').empty();

                // append data
                $('#tbody_employee_selected').append(str);

                // show table
                $('#emp_tbl_list').removeClass('d-none');

            },
            error: function() {
                console.log('Oppss something went wrong');
            }
        });

    });

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

    return diffDays - countSun;
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

 function DisplayEmployeeShift(emp_id,date_from,date_to, url){
    // let url = base_url+'ABACA/get_emp_shift';
    let str = "";
    let checkedIndentifier = '';
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    $.ajax({
        url: url,
        type: 'GET',
        data: {emp_id : emp_id,date_from: date_from, date_to: date_to},
        beforeSend: function(){
            // $('#emp_shift_body').empty().html('<tr><td colspan="10"><center><i class="fas fa-spinner fa-spin text-info m-auto" aria-hidden="true"></i></center></td></tr>');
            Swal.fire({
                width: "350",
                title: 'Fetching...',
                icon: "question",
                customClass: {
                    icon: 'no-border'
                },
                showClass: {
                    backdrop: 'swal2-noanimation', // disable backdrop animation
                    popup: '',                     // disable popup animation
                    icon: ''                       // disable icon animation
                },
                iconHtml: '<i class="fa-solid fa-spinner fa-spin-pulse fa-xs"></i>',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
        },
        success: function(result) {
            $.each(result[0], function (i) {

                let d = new Date(result[0][i].shift_date);

                checkedIndentifier = '';
                if(result[0][i].status == 1) checkedIndentifier = 'checked';
                
                if(i == 0){
                    $('#d_from_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());
                }
                else{
                    $('#d_to_display').val(monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear());
                }

                str += '<tr>\
                            <td>'+ monthNames[d.getMonth()] + " " + d.getDate()  + ", " + d.getFullYear()+'</td>\
                            <td><select id="u_shift_code[]" name="u_shift_code[]" class="form-control custom-select" value="'+ result[0][i].shift_code_id+'" required>';
                            str +='<option value="" selected disabled>Select shift code</option>';
                $.each(result[1], function (g) {
                    if(result[1][g].id == result[0][i].shift_code_id)str += '<option value="'+ result[1][g].id +'" selected>'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                    else str += '<option value="'+ result[1][g].id +'">'+result[1][g].shift_code+' - '+result[1][g].Description+'</option>';
                    
                    // str += '<option value="'+ result[1][i].id +'">'+result[1][i].shift_code+'</option>';
                    
                });
                    new_remarks = "";
                    if(result[0][i].remarks){
                        new_remarks = result[0][i].remarks;
                    }

                        //    <td><input class="form-control" type="text" name="u_shift_code[]" value="'+result[0][i].shift_code+'"></td></td>
                str += '</select><td><input type="number" name="u_break_hr[]" value="'+result[0][i].no_hr_break+'" any></td>\
                            <td><input class="form-control" type="text" name="u_remarks[]" value="'+new_remarks+'"></td>\
                            <td hidden><input type="text" name="u_shift_id[]" value="'+result[0][i].id+'"></td>\
                            <td class="text-center"><div class="form-check"><input name="u_status['+result[0][i].id+']" style="width: 20px;height: 20px;" class="form-check-input" value="'+result[0][i].id+'" type="checkbox" '+checkedIndentifier+'></div></td>\
                        </tr>';
            });

            // set empty
            $('#emp_shift_body').empty();

            // append data
            $('#emp_shift_table').append(str);
            Swal.close();
        },
        error: function() {
            console.log('Oppss something went wrong');
        }
    });
 }
