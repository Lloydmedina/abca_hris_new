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

$(function() {
    $('#myTable').DataTable({
        "iDisplayLength": 100
    });
    $(function() {
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
    });
});
$('#example23').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});
$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-primary mr-1');

$(document).ready(function(){

    // delete Leave

    $(document).on('click', '.deleteLeave', function(e) {
        
        let id = $(this).attr('id');
        let url = base_url + 'delete_leave';
        let btn_confirm = confirm("Delete Leave?");

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

    // Check if the checkbox hallday was checked
    $(document).on('change', '#is_half_day', function(e){

        let is_half_day = $('#is_half_day').val();

        if(is_half_day == 1){

            $('#is_half_day').val(0);
            $('#half_day_row').addClass('d-none');
            $('#whole_day_row').removeClass('d-none');

        }else{

            $('#is_half_day').val(1);
            $('#whole_day_row').addClass('d-none');
            $('#half_day_row').removeClass('d-none');
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