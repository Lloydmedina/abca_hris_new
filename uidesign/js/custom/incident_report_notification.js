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

$('#example23').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});
$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-sm btn-primary mr-1');


function get_dept_id(SysPK_Dept, DeptCode_Dept, Name_Dept, Location_Dept, Head_Dept, Position_Dept, AssistantHead_Dept, AssistPosition_Dept, Remarks_Dept){
	$('#Head_Dept').selectpicker('refresh');
	$('#Position_Dept').selectpicker('refresh');
	$('#AssistantHead_Dept').selectpicker('refresh');
	$('#AssistPosition_Dept').selectpicker('refresh');
	
	$('#DepartmentCode').val(DeptCode_Dept);
	$('#Department').val(Name_Dept);
	$('#Location').val(Location_Dept);

	$("#Head_Dept option[value='"+Head_Dept+"']").attr("selected", "selected");
	$('#Head_Dept').selectpicker('refresh');
	$("#Position_Dept option[value='"+Position_Dept+"']").attr("selected", "selected");
	$('#Position_Dept').selectpicker('refresh');
	$("#AssistantHead_Dept option[value='"+AssistantHead_Dept+"']").attr("selected", "selected");
	$('#AssistantHead_Dept').selectpicker('refresh');
	$("#AssistPosition_Dept option[value='"+AssistPosition_Dept+"']").attr("selected", "selected");
	$('#AssistPosition_Dept').selectpicker('refresh');
	$('textarea#remarks').val(Remarks_Dept);
	$('#SysPK_Dept').val(SysPK_Dept);
}