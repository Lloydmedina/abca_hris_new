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

function netPerformanceRating(){

    let part_1_score_percentage = $('#part_1_score_percentage').val();
    let part_2_score_percentage = $('#part_2_score_percentage').val();
    let p3_deduct_1 = $('#p3_deduct_1').val();
    let p3_deduct_2 = $('#p3_deduct_2').val();

    if(p3_deduct_1 == null || p3_deduct_1 == ''){
        p3_deduct_1 = 0;
    }
    if(p3_deduct_2 == null || p3_deduct_2 == ''){
        p3_deduct_2 = 0;
    }

    let total_score_percentage = parseFloat(part_1_score_percentage) + parseFloat(part_2_score_percentage);
    let total_deduct = parseFloat(p3_deduct_1) + parseFloat(p3_deduct_2);
    let netPerformanceRating = total_score_percentage - total_deduct;

    return netPerformanceRating;
}

function percentageScore(NFR = 0){
    
    let ret = 1;

    if(NFR >= 97) // 97 - 100
        ret = 15;
    else if(NFR >= 95) // 95 - 96
        ret = 14;
    else if(NFR >= 93) // 93 - 94
        ret = 13;
    else if(NFR >= 91.5) // 91.5 - 92
        ret = 12;
    else if(NFR >= 90) // 90 - 91.4
        ret = 11;
    else if(NFR >= 88) // 88 - 89
        ret = 10;
    else if(NFR >= 86) // 86 - 87
        ret = 9;
    else if(NFR >= 84) // 84 - 85
        ret = 8;
    else if(NFR >= 82) // 82 - 83
        ret = 7;
    else if(NFR >= 80) // 80 - 81
        ret = 6;
    else if(NFR >= 78) // 78 - 79
        ret = 5;
    else if(NFR >= 76) // 76 - 77
        ret = 4;
    else if(NFR >= 74) // 74 - 75
        ret = 13;
    else if(NFR >= 72) // 72 - 73
        ret = 13;
    else if(NFR >= 70) // 70 - 71
        ret = 1;

    return ret;
}

function salaryIncreased(){
    let basic_salary = $('#basic_salary').val();
    let net_performance_rating = percentageScore(netPerformanceRating());

    let salary_increased = (basic_salary / 100) * net_performance_rating;

    return salary_increased;
}

function compensationAdjustments(){
    let basic_salary = $('#basic_salary').val();
    let net_performance_rating = percentageScore(netPerformanceRating());

    let salary_increased = (basic_salary / 100) * net_performance_rating;

    let compensation_adjustments = parseFloat(salary_increased) + parseFloat(basic_salary);

    return compensation_adjustments;
}

function _allComputations(){
    $('#net_performance_rating').val(netPerformanceRating());
    $('#percentage_score').text(percentageScore(netPerformanceRating()));
    $('#percentage_score_val').val(percentageScore(netPerformanceRating())); // for input val
    $('#salary_increased').text(Number(salaryIncreased()).toLocaleString('en')); // With comma and decimals
    $('#compensation_adjustments').text(Number(compensationAdjustments()).toLocaleString('en')); // With comma and decimals
    $('#compensation_adjustments_val').val(compensationAdjustments()); // With comma and decimals
}

$(document).ready(function(){

    _allComputations();

    $(document).on('click', '.p3_no_1_score', function(){
        let val = $(this).val();
        $('#p3_deduct_1').val(parseFloat(val));

        _allComputations();
    });

    $(document).on('click', '.p3_no_2_score', function(){
        let val = $(this).val();
        $('#p3_deduct_2').val(parseFloat(val));

        _allComputations();
    });

    $(document).on('change', '#employee_id', function(){

        let url = base_url+'get_this_employee';
        let employee = $(this).val();
    
        //do ajax
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {employee:employee},
            success: function(result) {

                onBeforeunload();

                $('#employee_name').text(result[0].Name_Empl);
                $('#employee_position').text(result[0].Position_Empl);
                $('#employee_date_hired').text(result[0].DateHired_Empl);
                
            },
            error: function() {
                console.log('Oppss something went wrong');
            }
        });

    });

    $(document).on('click', '#btn_next', function(){

        offBeforeunload();

        $('#btn_submit').click();

    });
});