$(document).ready(function(){

	$('#list').DataTable();

	$('#entry_reset_id').click(function(){
		if (confirm('Are you sure you want to reset?')) {
            $('#daily_rate').addClass("d-none");
			$('#daily_rate').val(0);
			$('#monthly_rate').removeClass("d-none");
			$('#entry_reset_button').click();//click reset button to clear the fields
        }
	});
	//entry position modal
	$('#rate_selector').change(function(){
		let val = $(this).val();
		if (val == 1) {
			$('#daily_rate').addClass("d-none");
			$('#daily_rate').val(0);
			$('#monthly_rate').removeClass("d-none");
		}
		else{
			$('#monthly_rate').addClass("d-none");
			$('#monthly_rate').val(0);
			$('#daily_rate').removeClass("d-none");
		}
	});

	//update position modal
	$('#update_rate_selector').change(function(){
		let val = $(this).val();
		if (val == 1) {
			$('#update_daily_rate').addClass("d-none");
			$('#update_daily_rate').val(0);
			$('#update_monthly_rate').removeClass("d-none");
		}
		else{
			$('#update_monthly_rate').addClass("d-none");
			$('#update_monthly_rate').val(0);
			$('#update_daily_rate').removeClass("d-none");
		}
	});
});

function get_position_id(id,Position_Code, Position_Empl, RatePerDay_Empl, BasicSalary_Empl, DoleRate_Empl){
	//reset all fields first
	//$('form :input').val('');

	if (RatePerDay_Empl > 0) {
		//select default daily 
		$("#update_rate_selector option[value=2]").prop("selected", "selected");
		//select daily rate first
		$('#update_monthly_rate').addClass("d-none");
		$('#update_monthly_rate').val(0);
		$('#update_daily_rate').removeClass("d-none");
	}else{
		//select default monthly
		$("#update_rate_selector option[value=1]").prop("selected", "selected");
		//select monthly rate first
		$('#update_daily_rate').addClass("d-none");
		$('#update_daily_rate').val(0);
		$('#update_monthly_rate').removeClass("d-none");
	}
	

	$('#id').val(id);
	$('#Position_Code').val(Position_Code);
	$('#Position_Empl').val(Position_Empl);
	$('#RatePerDay_Empl').val(RatePerDay_Empl);
	$('#BasicSalary_Empl').val(BasicSalary_Empl);
	$('#DoleRate_Empl').val(DoleRate_Empl);
}