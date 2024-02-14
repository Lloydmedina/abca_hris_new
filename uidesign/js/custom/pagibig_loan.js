let base_url = window.location.origin + '/' + window.location.pathname.split('/')[1] + '/';
if(window.location.origin != "http://localhost"){
    base_url = window.location.origin + '/';
}

$(function() {
	$('.selectpicker').selectpicker();
    $('#myTable').DataTable();
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

$('#loan_list').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});

$('#deduction_list').DataTable();

$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-primary mr-1');

$(document).ready(function(){

    $(document).on('click', '.edit_pagibig', function(){
        let employee = $(this).attr('employee');
        let amount_deduction = $(this).attr('amount_deduction');
        let pagibig_loan_date = $(this).attr('pagibig_loan_date');
        let loan_amount = $(this).attr('loan_amount');
        let pagibig_loan_id = $(this).attr('pagibig_loan_id');
        let date_from = $(this).attr('date_from');
        let date_to = $(this).attr('date_to');
        let remarks = $(this).attr('remarks');

        $('#employee_2').val(employee);
        $('#amount_deduction_2').val(amount_deduction);
        $('#pagibig_loan_date_2').val(pagibig_loan_date);
        $('#loan_amount_2').val(loan_amount);
        $('#pagibig_loan_id').val(pagibig_loan_id);
        $('#date_from_2').val(date_from);
        $('#date_to_2').val(date_to);
        $('#remarks_2').val(remarks);
    });

    $(document).on('click', '.view_deduction', function(){

        let employee = $(this).attr('employee');
        let pagibig_loan_id = $(this).attr('pagibig_loan_id');
        let url = base_url + 'get_deduction_history';

        let str = '';
        let deduction;

        $('#emp_name_deduction').text(employee);

        if(pagibig_loan_id){

           $('#deduction_list').DataTable().clear().draw();

            $.ajax({
                url: url,
                type: 'POST',
                data: {pagibig_loan_id:pagibig_loan_id, type:'pagibig'},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {

                    if(result.length){

                        for (deduction in result) {
                            str += '<tr>\
                                    <td>'+(parseInt(deduction) + 1)+'</td>\
                                    <td class="text-right">'+result[deduction].total_amount+'</td>\
                                    <td class="text-right">'+result[deduction].current_deduction+'</td>\
                                    <td class="text-right">'+result[deduction].current_balance+'</td>\
                                    <td class="text-right">'+result[deduction].remaining_balance+'</td>\
                                    <td>'+result[deduction].date_deducted+'</td>\
                                </tr>';
                        }

                        // set empty
                        $('#deduction_history_tbl').empty();

                        // append data
                        $('#deduction_history_tbl').append(str);
                    }
                },
                error: function(result){
                    console.log(result);
                }

            });
        }
        else{
            alert('Something went wrong!');
        }

    });

    $(document).on('click', '.add_deduction', function(){
        let employee = $(this).attr('employee');
        let pagibig_loan_id = $(this).attr('pagibig_loan_id');

        $('#emp_pagibig_loan_id').val(pagibig_loan_id);
        $('#emp_name_to_deduct').text(employee);
    });
});