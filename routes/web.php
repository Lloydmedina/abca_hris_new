<?php

// UNCOMMENT FOR MAINTENACE MODE
// @include view('Pages.maintenance');

// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'usersession'], function () {
    // Uses User Session Middleware

    Route::get('/page-locked', 'Settings\SettingsController@page_locked_view')->name('page_locked');
    Route::post('/page-unlock', 'Settings\SettingsController@page_unlock')->name('page_unlock');

    // Put route here for admin only
    Route::group(['middleware' => 'admin'], function () {

        Route::get('/datatables', function () {
            if (!Session::has('logged')) {return redirect('/login');}
            return view('Pages.datatables');
        });

        // users
        // Route::get('/add-user', 'User\Users@add_user_view')->name('add   _user');
        
        //UAER LISTS
        Route::get('/users', 'User\Users@user_list_view')->name('users');
        //ACTIVATE USER
        Route::get('activate_user/{id}', 'User\Users@activate_user')->name('activate-user');
        //DEACTIVATE USER
        Route::get('deactivate_user/{id}', 'User\Users@deactivate_user')->name('deactivate-user');

        //-----Start Employee Setup Routes----------
        //ADD EMPLOYEE VIEW
        Route::get('/add-employee', 'Employee\Employee@add_employee_view')->name('add-employee');
        
        //EMPLOYEE LIST VIEW
        Route::get('/employees', 'Employee\Employee@employees_view')->name('employees');
        //UPDATE EMPLOYEE VIEW
        Route::get('/update-employee', 'Employee\Employee@update_employee_view')->name('update-employee');
        // SETUP EMPLOYEE USERS
        Route::get('import-credentials', 'Employee\Employee@import_credentials')->name('import_credentails');
        //-----End Employee Setup Routes------------


        // Trainings
        Route::get('/trainings', 'Training\Training@trainings')->name('trainings');
        Route::get('/training-calendar', 'Training\Training@training_calendar')->name('training_calendar');
        Route::get('/training-request', 'Training\Training@training_request')->name('training_request');
        Route::get('/new-training', 'Training\Training@new_training')->name('new_training');
        
        Route::get('/training/{id}/attendees', 'Training\Training@traning_attendees')->name('traning_attendees');
        
        // Delete training
        Route::get('/training/delete/{id}', 'Training\Training@delete_training')->name('delete_training');
        // Delete attendee
        Route::get('/attendee/delete/{id}', 'Training\Training@delete_attendee')->name('delete_attendee');
        

        //DICIPLINE MANAGEMENT
        Route::get('/view_code_of_conduct', 'DiciplineManagement\DiciplineManagementController@view_code_of_conduct')->name('view_code_of_conduct');
        Route::get('/incident_report', 'DiciplineManagement\DiciplineManagementController@incident_report')->name('incident_report');
        Route::get('/disciplinary-action-form', 'DiciplineManagement\DiciplineManagementController@disciplinary_action_form')->name('disciplinary_action_form');
        Route::get('/disciplinary-action-delete', 'DiciplineManagement\DiciplineManagementController@delete_da');
        // BIOMETRICS
        Route::get('/employee-biometrics', 'DTR\DTRController@employee_biometrics')->name('employee_biometrics');

        //-----Start Line Leader Setup Routes----------
        Route::get('/line-leader', 'Shift\ShiftController@line_leader')->name('line-leader');

        Route::get('/employee-shift', 'Shift\ShiftController@employee_shift')->name('employee-shift');

        //-----End Line Leader Setup Routes------------


        //-----Start DTR Setup Routes----------

        Route::get('/dtr-entry', 'DTR\DTRController@dtr_entry')->name('dtr_entry');

        Route::get('/dtr_list', 'DTR\DTRController@dtr_list')->name('dtr_list');

        Route::get('/dtr_summary', 'DTR\DTRController@dtr_summary')->name('dtr_summary');

        Route::get('/import_dtr_main_office', 'DTR\DTRController@import_dtr_main_office');
        Route::get('/normalize_dtr', 'DTR\DTRController@normalize_dtr');
        Route::get('/incomplete_dtr_list', 'DTR\DTRController@incomplete_dtr_list')->name('incomplete_dtr_list');
        Route::get('/late_dtr_list', 'DTR\DTRController@late_dtr_list')->name('late_dtr_list');
        Route::get('/inquire_dtr', 'DTR\DTRController@inquire_dtr');
        //IMPORT ATTENDANCE
        Route::get('/import_dtr', function(){
            return view('DTR.import_dtr');
        });
        
        //-----End DTR Setup Routes------------

        //-------START INVENTORY ROUTES------------
        Route::get('/import-inventory', function(){
            return view('inventory.import_inventory');
        });
        
        //-----Start Company Setup Routes----------
        //COST CENTER VIEW
        Route::get('/cost_center', 'CompanySetup\CompanySetup@cost_center')->name('cost_center');
        
        //DEPARTMENT VIEW
        Route::get('/department', 'CompanySetup\CompanySetup@department')->name('department');
        
        
        //POSITION VIEW
        Route::get('/position', 'CompanySetup\CompanySetup@position')->name('position');
        

        Route::get('/company', 'CompanySetup\CompanySetup@company')->name('company');

        Route::get('/company_group', 'CompanySetup\CompanySetup@company_group')->name('company_group');
        

        Route::get('/outlet', 'CompanySetup\CompanySetup@outlet')->name('outlet');
        
        Route::get('outlet/delete/{id}', 'CompanySetup\CompanySetup@delete_outlet')->name('delete-outlet');

        // ABSETEEISM
        Route::get('/absenteeism', 'Absent\AbsenteeismController@index')->name('absenteeism');
        Route::get('/add-absenteeism', 'Absent\AbsenteeismController@add_absent_view')->name('add_absent_view');
        
        //-----End Absenteeism Routes------------

        // Leaves
        Route::get('/leave_type', 'Leaves\LeavesController@leave_type')->name('leave_type');
        

        Route::get('/leave', 'Leaves\LeavesController@index')->name('leave');
        Route::get('/leave-entry', 'Leaves\LeavesController@leave_entry')->name('leave_entry');
        Route::get('/leave_monitoring', 'Leaves\LeavesController@leave_monitoring')->name('leave_monitoring');
        // End Leaves

        // Overtime
        Route::get('/overtime', 'Overtime\OvertimeController@index')->name('overtime');
        Route::get('/add-overtime', 'Overtime\OvertimeController@add_overtime_view')->name('add_overtime_view');


        //HOLIDAY VIEW
        Route::get('/holiday_setup', 'Holiday\HolidayController@holiday_setup')->name('holiday_setup');
        
        //-----End HOLIDAY Routes------------

        // Performance Evaluation
        Route::get('/employee-evaluation-1', 'Employee\Employee@evaluation_1')->name('evaluation_1');
        Route::get('/employee-evaluation-2', 'Employee\Employee@evaluation_2_1')->name('evaluation_2_1');
        
        Route::get('/employee-evaluations', 'Employee\Employee@employee_evaluations')->name('employee_evaluations');
        Route::get('/employee-evaluation', 'Employee\Employee@view_this_eval')->name('view_employee_evaluation');


        // Personel Iitenirary
        Route::get('/personel_itenirary', 'Employee\Employee@personel_itenirary')->name('personel_itenirary');
        // Approve PI
        Route::get('/approve_pi', 'Employee\Employee@approve_pi');
        // Reject PI
        Route::get('/reject_pi', 'Employee\Employee@reject_pi');
        


        // Shift Code
        Route::get('/shift-code', 'Shift\ShiftController@shift_code')->name('shift_code');
        
        Route::get('/shift-monitoring', 'Shift\ShiftController@index')->name('shift_monitoring');

        
        // APPLICANT
        Route::get('/applicants', 'Applicant\ApplicantController@applicants')->name('applicants');
        Route::get('/applicant-decline', 'Applicant\ApplicantController@decline');
        Route::get('/applicant-hire', 'Applicant\ApplicantController@hire');
        Route::get('/applicant-delete', 'Applicant\ApplicantController@delete');


        //CASH ADVANCE VIEW
        Route::get('/cash_advance', 'CashAdvance\CashAdvanceController@cash_advance')->name('cash_advance');
        

        //ATD
        Route::get('/atd', 'CashAdvance\CashAdvanceController@atd')->name('atd');
        
        //-----End Cash Advances Routes------------

        //LOAN VIEW
        Route::get('/sss_loan', 'Loan\LoanController@sss_loan')->name('sss_loan');
       
        Route::get('/moorgate_loan', 'Loan\LoanController@moorgate_loan')->name('moorgate_loan');

        Route::get('/access-loan', 'Loan\LoanController@access_loan')->name('access_loan');


        //-----Start Payroll Routes----------
        Route::get('/payroll_entry', 'Payroll\PayrollController@payroll_entry')->name('payroll_entry');
        
        Route::get('/print_payroll', 'Payroll\PayrollController@print_payroll');
        Route::get('/payroll_list', 'Payroll\PayrollController@payroll_list')->name('payroll_list');
        Route::get('/alphalist', 'Payroll\PayrollController@alphalist');
        Route::get('/taxdue_list', 'Payroll\PayrollController@taxdue_list')->name('taxdue_list');

        Route::get('/payroll_deduction', 'Payroll\PayrollController@payroll_deduction');
        
        // Service charge
        Route::get('/service-charge', 'Payroll\PayrollController@service_charge')->name('service_charge');
        // other income entry
        Route::get('/other-income', 'Payroll\PayrollController@other_income')->name('other_income');
        // other income entry
        Route::get('/adjustments', 'Payroll\PayrollController@adjustments')->name('adjustments');
        // Deduction entry
        Route::get('/deduction', 'Payroll\PayrollController@deduction')->name('deduction');
        
        //-----End Payroll Routes------------

        // ADMIN HERE

        //SUPER ADMIN
        Route::get('/user_privilege', 'SuperAdmin\SuperAdminController@user_privilege')->name('user_privilege');
        Route::post('/user_privilege_update', 'SuperAdmin\SuperAdminController@user_privilege_update')->name('user_privilege_update');

        // System lock status
        // System lock
        Route::get('/system-lock', 'Settings\SettingsController@system_lock')->name('system-lock');


        Route::get('/sss-contributions', 'Contributions\ContributionsController@sss')->name('sss-contributions');
        Route::get('/philhealth-contributions', 'Contributions\ContributionsController@philhealth')->name('philhealth-contributions');
        Route::get('/pagibig-contributions', 'Contributions\ContributionsController@pagibig')->name('pagibig-contributions');
        Route::get('/1601-c', 'Contributions\ContributionsController@_1601c')->name('1601-c');

    });
    // END ADMIN ROUTE
    

    // Add moorgate loan
    Route::post('/add_moorgate_loan','Loan\LoanController@add_moorgate_loan');
    // Update moorgate loan
    Route::post('/update_moorgate_loan', 'Loan\LoanController@update_moorgate_loan');
    // Delete moorgate laon
    Route::get('/delete_moorgate_loan', 'Loan\LoanController@delete_moorgate_loan');

    // Add access loan
    Route::post('/add_access_loan','Loan\LoanController@add_access_loan');
    // Update access loan
    Route::post('/update_access_loan', 'Loan\LoanController@update_access_loan');
    // Delete access laon
    Route::get('/delete_access_loan', 'Loan\LoanController@delete_access_loan');

    Route::get('/pagibig_loan', 'Loan\LoanController@pagibig_loan')->name('pagibig_loan');
    
    // Delete pagibig laon
    Route::get('/delete_pagibig_loan', 'Loan\LoanController@delete_pagibig_loan');

    //-----End LOAN Routes------------

    Route::get('/print_dtr_list', 'DTR\DTRController@print_dtr_list')->name('print_dtr_list');
    // START POST METHOD

    // all post method put outside admin route except user_privilege_update

    // Add & delete for service charge, other income & deductions
    Route::post('/add_service_fee_other_deduction', 'Payroll\PayrollController@add_service_fee_other_deduction');
    // update
    Route::post('/update_service_fee_other_deduction', 'Payroll\PayrollController@update_service_fee_other_deduction');
    Route::post('/delete_service_charge_other_deduction', 'Payroll\PayrollController@delete_service_charge_other_deduction')->name('delete_service_charge_other_deduction');

    Route::post('/upload_service_fee_other', 'Payroll\PayrollController@upload_service_fee_other');
    Route::post('/save_payroll', 'Payroll\PayrollController@save_payroll');
    Route::post('/upload_payroll_deduction', 'Payroll\PayrollController@upload_payroll_deduction');

    Route::post('/add_pagibig_loan','Loan\LoanController@add_pagibig_loan');

    // Update Pagibig loan
    Route::post('/update_pagibig_loan', 'Loan\LoanController@update_pagibig_loan');

    // Get deduction History
    Route::post('/get_deduction_history', 'Loan\LoanController@get_deduction_history');
    Route::post('/add_deduction_loan', 'Loan\LoanController@add_deduction_loan');

    // Add sss loan
    Route::post('/add_sss_loan','Loan\LoanController@add_sss_loan');
    // Update sss loan
    Route::post('/update_sss_loan', 'Loan\LoanController@update_sss_loan');
    // Delete sss laon
    Route::get('/delete_sss_loan', 'Loan\LoanController@delete_sss_loan');

    // ATD
    Route::post('/add_atd','CashAdvance\CashAdvanceController@add_atd');
    // UPDATE
    Route::post('/update_atd','CashAdvance\CashAdvanceController@update_atd');
    // Delete CA
    Route::post('/delete_atd','CashAdvance\CashAdvanceController@delete_atd')->name('delete_atd');

    // CASH ADVANCE
    Route::post('/add_cash_advance','CashAdvance\CashAdvanceController@add_cash_advance');
    // Delete CA
    Route::post('/delete_ca','CashAdvance\CashAdvanceController@delete')->name('delete_ca');
    
    //-----End Cash Advances Routes------------

    // Save shift code
    Route::post('/save_shift_code', 'Shift\ShiftController@save_shift_code')->name('save_shift_code');
    // Update
    Route::post('/update_shift_code', 'Shift\ShiftController@update_shift_code')->name('update_shift_code');
    // Delete
    Route::get('shift-code/delete/{id}', 'Shift\ShiftController@delete_shift_code')->name('delete-shift-code');
    // Restore
    Route::get('shift-code/restore/{id}', 'Shift\ShiftController@restore_shift_code')->name('restore-shift-code');
    


    //UPDATE USERTYPE
    Route::post('update_emp_type', 'User\Users@update_emp_type')->name('update_emp_type');
    //ADD USER PROCESS
    Route::post('/add_user', 'User\Users@add_user_pro')->name('add_user');

    //ADD EMPLOYEE PROCESS
    Route::post('/add_employee', 'Employee\Employee@add_employee_process')->name('add_employee');

    //UPDATE EMPLOYEE PROCESS
    Route::post('/update_employee', 'Employee\Employee@update_employee_process')->name('update_employee');

    // ADD NEW TRAINING
    Route::post('/add-new-training', 'Training\Training@add_new_traning')->name('add_new_traning');

    Route::post('/add-new-attendees', 'Training\Training@add_attendees')->name('add_attendees');

    // Update attendee
    Route::post('/attendee/update/', 'Training\Training@update_attendee')->name('update_attendee');


    Route::post('/incident_report', 'DiciplineManagement\DiciplineManagementController@save')->name('incident_report.save');
    Route::post('/disciplinary_action', 'DiciplineManagement\DiciplineManagementController@disciplinary_action_save')->name('disciplinary_action.save');

    // DTR
    Route::post('/import_dtr', 'DTR\DTRController@import_dtr')->name('import_dtr');

    Route::post('/import_dtr_dat', 'DTR\DTRController@import_dtr_dat')->name('import_dtr_dat');

    Route::post('/import_dtr_excel', 'DTR\DTRController@import_dtr_excel')->name('import_dtr_excel');

    Route::post('/manual_entry_dtr', 'DTR\DTRController@manual_entry_dtr');

    Route::post('/save_dtr', 'DTR\DTRController@save_dtr');

    Route::post('/save_dtr2', 'DTR\DTRController@save_dtr2');

    Route::post('/upload_dtr_summary', 'DTR\DTRController@upload_dtr_summary');

    Route::post('/get_dtr_main_office', 'DTR\DTRController@get_dtr_main_office');

    Route::post('/save_dtr_main_office', 'DTR\DTRController@save_dtr_main_office');
    route::post('/add_normalize_dtr','DTR\DTRController@add_normalize_dtr');

    route::post('/update_dtr','DTR\DTRController@update_dtr');

    route::post('/update_dtr_summary','DTR\DTRController@update_dtr_summary');

    //SAVE DTR PROCESS
    Route::post('/save_dtr', 'DTR\DTRController@save_dtr')->name('save_dtr');

    Route::post('/import-inventory', 'Inventory\InventoryController@import_inventory')->name('import-inventory');
    //SAVE INVENTORY PROCESS
    Route::post('/save_inventory', 'Inventory\InventoryController@save_inventory')->name('save_inventory');

    //ADD COST CENTER
    Route::post('/add_cost_center', 'CompanySetup\CompanySetup@add_cost_center');

    //ADD DEPARTMENT
    Route::post('/add_department', 'CompanySetup\CompanySetup@add_department')->name('add_department');
    //-----END INVENTORY ROUTES---------------

    //UPDATE DEPARTMENT
    Route::post('/update_department', 'CompanySetup\CompanySetup@update_department')->name('update_department');

    //ADD POSITION
    Route::post('/add_position', 'CompanySetup\CompanySetup@add_position')->name('add_position');

    //UPDATE POSITION
    Route::post('/update_position', 'CompanySetup\CompanySetup@update_position')->name('update_position');

    Route::get('/employee_level', 'CompanySetup\CompanySetup@employee_level')->name('employee_level');
    Route::post('/add_employee_level', 'CompanySetup\CompanySetup@add_employee_level')->name('add_employee_level');

    Route::post('/add_company', 'CompanySetup\CompanySetup@add_company')->name('add_company');

    Route::post('/add_company_group', 'CompanySetup\CompanySetup@add_company_group')->name('add_company_group');

    Route::post('/add_outlet', 'CompanySetup\CompanySetup@add_outlet')->name('add_outlet');
    Route::post('/update_outlet', 'CompanySetup\CompanySetup@update_outlet')->name('update_outlet');

    Route::post('/absenteeism', 'Absent\AbsenteeismController@get')->name('ab_get_employee');
    Route::post('/save_absent', 'Absent\AbsenteeismController@save')->name('save_absenteeism');
    Route::post('/delete_ab','Absent\AbsenteeismController@delete')->name('delete_ab');

    Route::post('/add_leave_type', 'Leaves\LeavesController@add_leave_type');

    Route::post('/add_holiday_setup','Holiday\HolidayController@add_holiday_setup');
    Route::post('/update_holiday','Holiday\HolidayController@update_holiday');

    Route::post('/employee-evaluation-2', 'Employee\Employee@evaluation_2')->name('evaluation_2');
    Route::post('/save_print_evaluation', 'Employee\Employee@save_print_evaluation')->name('save_print_evaluation');

    Route::post('/add_personel_itenirary', 'Employee\Employee@add_personel_itenirary')->name('add_personel_itenirary');

    // END POST METHOD
     





    
    //EMPLOYEE INFORMATION VIEW
    Route::get('/employee', 'Employee\Employee@employee_info_view')->name('employee_info');

    //LOGOUT
    Route::get('/logout', 'Logout@user_logout')->name('logout');

    //DEFAULT ROUTE DASHBOARD
    Route::get('/', 'Dashboard@dashboard');

    route::post('update_weekly_hour','Dashboard@update_weekly_hour');

    //DASHBOARD
    Route::get('/dashboard', 'Dashboard@dashboard')->name('dashboard');

    // //SUPER ADMIN
    Route::get('/user_access_setup', 'SuperAdmin\SuperAdminController@user_access_setup')->name('user_access_setup');

    Route::get('/forgot-password', function () {
        return view('Pages.forgot_password');
    })->name('forgot_password');

    

    //AJAX REQUEST
    Route::get('get_user_details', 'User\Users@get_user_details');



    // Ajax get outlet approver
    Route::post('/outlet_approver', 'CompanySetup\CompanySetup@outlet_approver')->name('outlet_approver');
    // Add approver
    Route::post('/add_outlet_approver', 'CompanySetup\CompanySetup@add_outlet_approver')->name('add_outlet_approver');
    // Remove approver
    Route::post('/remove_outlet_approver', 'CompanySetup\CompanySetup@remove_outlet_approver')->name('remove_outlet_approver');


    // Payslip
    Route::get('/payslip', 'Payroll\PayslipController@payslip')->name('payslip');
    // End payslip

    //-----Start Leave Routes----------
    //Payrol Entry VIEW

    //-----End Leave Routes------------

    // Biometric logs // approver
    Route::get('/staff-biometric-logs', 'DTR\DTRController@staff_biometric_logs')->name('staff_biometric_logs');
    // Staff DTR
    Route::get('/staff-dtr-logs', 'DTR\DTRController@staff_dtr_logs')->name('staff_dtr_logs');

    // OVERTIME
    Route::get('/overtime-request', 'Overtime\OvertimeController@overtime_request')->name('overtime_request');
    Route::post('/overtime', 'Overtime\OvertimeController@get')->name('get_employee');
    Route::post('/save_overtime', 'Overtime\OvertimeController@save')->name('save_overtime');
    Route::post('/approved_ot', 'Overtime\OvertimeController@approved_ot');
    Route::post('/delete_ot','Overtime\OvertimeController@delete')->name('delete_ot');

    //NORMAL EMPLOYEE
    Route::get('/my-shift', 'Shift\ShiftController@shift')->name('my_shift');
    
    Route::get('/biometrics', 'DTR\DTRController@biometrics')->name('biometrics');
    Route::get('/my-dtr', 'DTR\DTRController@dtr')->name('my_dtr');

    Route::post('/file_change_schedule_request', 'Shift\ShiftController@file_change_schedule_request')->name('file_change_schedule_request');

    // Approver side
    Route::post('/approver_change_schedule', 'Shift\ShiftController@approver_change_schedule')->name('approver_change_schedule');

    Route::get('/staff-shift', 'Shift\ShiftController@shift_outlet')->name('shift_outlet');
    Route::get('/update-staff-shift', 'Shift\ShiftController@update_staff_shift_v2')->name('update_staff_shift_v2');
    Route::match(['get', 'post'],'/fix-staff-shift', 'Shift\ShiftController@fix_staff_shift')->name('fix_staff_shift');


    Route::get('/my-overtime', 'Overtime\OvertimeController@my_overtime')->name('my_overtime');
    Route::get('/file-overtime', 'Overtime\OvertimeController@file_overtime')->name('file_overtime');
    Route::post('/file-overtime-request', 'Overtime\OvertimeController@file_overtime_request')->name('file_overtime_request');

    Route::get('/update-file-overtime', 'Overtime\OvertimeController@file_overtime_to_update')->name('file_overtime_to_update');
    Route::post('/file-overtime-request-update', 'Overtime\OvertimeController@file_overtime_update')->name('file_overtime_update');

    // Approver
    Route::get('/update-file-overtime-approver', 'Overtime\OvertimeController@file_overtime_to_update_approver')->name('file_overtime_to_update_approver');

    // Ajax
    Route::post('/approve_overtime','Overtime\OvertimeController@approve')->name('approve_overtime');
    Route::post('/reject_overtime','Overtime\OvertimeController@reject')->name('reject_overtime');
    Route::post('/delete_overtime','Overtime\OvertimeController@delete')->name('delete_overtime');

    // TIME PASS SLIP
    Route::get('/my-time-pass', 'RequestAF\RequestAttendanceForm@my_time_pass')->name('my_time_pass');
    Route::get('/file-time-pass', 'RequestAF\RequestAttendanceForm@file_time_pass')->name('file_time_pass');
    Route::post('/file-time-pass-request', 'RequestAF\RequestAttendanceForm@file_time_pass_request')->name('file_time_pass_request');

    Route::get('/update-file-time-pass', 'RequestAF\RequestAttendanceForm@file_time_pass_to_update')->name('file_time_pass_to_update');
    Route::post('/file-time-pass-request-update', 'RequestAF\RequestAttendanceForm@file_time_pass_update')->name('file_time_pass_update');

    // approver
    Route::get('/update-file-time-pass-approver', 'RequestAF\RequestAttendanceForm@file_time_pass_to_update_approver')->name('file_time_pass_to_update_approver');
    
    // AJAX
    Route::post('/delete_time_pass','RequestAF\RequestAttendanceForm@delete_time_pass')->name('delete_time_pass');
    Route::post('/approve_time_pass','RequestAF\RequestAttendanceForm@approve_time_pass')->name('approve_time_pass');
    Route::post('/reject_time_pass','RequestAF\RequestAttendanceForm@reject_time_pass')->name('reject_time_pass');

    // APPROVE MULTIPLE PASS SLPIP
    Route::post('/approve_multiple_tpass','RequestAF\RequestAttendanceForm@approves_multiple_pass_slip')->name('approves_multiple_pass_slip');

    // UNDERTIME
    Route::get('/my-undertime', 'RequestAF\RequestAttendanceForm@my_undertime')->name('my_undertime');
    Route::get('/file-undertime', 'RequestAF\RequestAttendanceForm@file_undertime')->name('file_undertime');
    Route::post('/file-undertime-request', 'RequestAF\RequestAttendanceForm@file_undertime_request')->name('file_undertime_request');

    Route::get('/update-file-undertime', 'RequestAF\RequestAttendanceForm@file_undertime_to_update')->name('file_undertime_to_update');
    Route::post('/file-undertime-request-update', 'RequestAF\RequestAttendanceForm@file_undertime_update')->name('file_undertime_update');

    // Approver
    Route::get('/update-file-undertime-approver', 'RequestAF\RequestAttendanceForm@file_undertime_to_update_approver')->name('file_undertime_to_update_approver');
    
    // AJAX
    Route::post('/delete_undertime','RequestAF\RequestAttendanceForm@delete_undertime')->name('delete_undertime');
    Route::post('/approve_undertime','RequestAF\RequestAttendanceForm@approve_undertime')->name('approve_undertime');
    Route::post('/reject_undertime','RequestAF\RequestAttendanceForm@reject_undertime')->name('reject_undertime');

    // AJAX FOR CHANGE SCHED
    Route::post('/approve_change_schedule','RequestAF\RequestAttendanceForm@approve_change_schedule')->name('approve_change_schedule');
    Route::post('/reject_change_schedule','RequestAF\RequestAttendanceForm@reject_change_schedule')->name('reject_change_schedule');

    // OBT
    Route::get('/my-obt', 'RequestAF\RequestAttendanceForm@my_obt')->name('my_obt');
    Route::get('/file-obt', 'RequestAF\RequestAttendanceForm@file_obt')->name('file_obt');
    Route::post('/file-obt-request', 'RequestAF\RequestAttendanceForm@file_obt_request')->name('file_obt_request');

    Route::get('/update-file-obt', 'RequestAF\RequestAttendanceForm@file_obt_to_update')->name('file_obt_to_update');
    Route::post('/file-obt-request-update', 'RequestAF\RequestAttendanceForm@file_obt_update')->name('file_obt_update');

    // approver
    Route::get('/update-file-obt-approver', 'RequestAF\RequestAttendanceForm@file_obt_to_update_approver')->name('file_obt_to_update_approver');

    // AJAX
    Route::post('/delete_obt','RequestAF\RequestAttendanceForm@delete_obt')->name('delete_obt');
    Route::post('/approve_obt','RequestAF\RequestAttendanceForm@approve_obt')->name('approve_obt');
    Route::post('/reject_obt','RequestAF\RequestAttendanceForm@reject_obt')->name('reject_obt');


    // Time pass req
    Route::get('/time-pass-request', 'RequestAF\RequestAttendanceForm@time_pass_request')->name('time_pass_request');
    // undertime req
    Route::get('/undertime-request', 'RequestAF\RequestAttendanceForm@undertime_request')->name('undertime_request');
    // Change sched
    Route::get('/change-schedule-request', 'RequestAF\RequestAttendanceForm@change_schedule_request')->name('change_schedule_request');
    // OBT
    Route::get('/obt-request', 'RequestAF\RequestAttendanceForm@obt_request')->name('obt_request');


    


    // clear_shift_session
    Route::get('/clear_shift_session', 'Shift\ShiftController@clear_shift_session')->name('clear_shift_session');
    // entry
    Route::get('/shift-entry', 'Shift\ShiftController@shift_entry')->name('shift_entry');
    // Save shift
    Route::post('/save_shift', 'Shift\ShiftController@save_shift')->name('save_shift');
    // Upload shift
    Route::post('/upload_emp_shift', 'Shift\ShiftController@upload_emp_shift')->name('upload_emp_shift');
    // Delete
    Route::post('/delete_shift','Shift\ShiftController@delete')->name('delete_shift');

    // ADD SHIFT V2
    Route::get('/shift-entry-v2', 'Shift\ShiftController@shift_entry_v2')->name('shift_entry_v2');
    

    // entry
    Route::post('/save_emp_shift', 'Shift\ShiftController@save_emp_shift')->name('save_emp_shift');
    Route::post('/save_emp_shift_v2', 'Shift\ShiftController@save_emp_shift_v2')->name('save_emp_shift_v2');
    Route::get('/get_emp_shift', 'Shift\ShiftController@get_emp_shift')->name('get_emp_shift');
    Route::post('/update_emp_shift', 'Shift\ShiftController@update_emp_shift')->name('update_emp_shift');

    Route::post('/update_emp_shift_v2', 'Shift\ShiftController@update_emp_shift_v2')->name('update_emp_shift_v2');
    // import_shift
    Route::post('/import_shift', 'Shift\ShiftController@view_emp_shift_uploads')->name('import_shift');
    Route::post('/save_import_shift', 'Shift\ShiftController@save_emp_shift_uploads')->name('save_import_shift');

    // 
    Route::post('/get_emp_shift_2', 'Shift\ShiftController@get_emp_shift_2')->name('get_emp_shift_2');


    // Approver
    Route::get('/leave-request','Leaves\LeavesController@leave_request')->name('leave_request');
    Route::post('/approve_leave','Leaves\LeavesController@approve')->name('approve_leave');
    Route::post('/reject_leave','Leaves\LeavesController@reject')->name('reject_leave');

    //-----Start Leave Routes----------
    Route::get('/my-leave', 'Leaves\LeavesController@my_leave')->name('my_leave');
    Route::get('/file-leave', 'Leaves\LeavesController@leave_entry')->name('file_leave');
    Route::post('/save_leaves', 'Leaves\LeavesController@save')->name('save_leaves');
    Route::post('/delete_leave','Leaves\LeavesController@delete')->name('delete_leave');

    Route::get('/update-file-leave', 'Leaves\LeavesController@file_leave_to_update')->name('file_leave_to_update');
    Route::post('/file-leave-update','Leaves\LeavesController@file_leave_update')->name('file_leave_update');

    // Approver
    Route::get('/update-file-leave-approver', 'Leaves\LeavesController@file_leave_to_update_approver')->name('file_leave_to_update_approver');
    
    // End leave


    // Trainings Employee side
    Route::get('/my-trainings', 'Training\Training@my_trainings')->name('my_trainings');
    Route::get('/my-training/{id}/result', 'Training\Training@traning_attendee_result')->name('traning_attendee_result');
    Route::get('/my-training-calendar', 'Training\Training@my_training_calendar')->name('my_training_calendar');

    //
    Route::post('/get_this_employee', 'Employee\Employee@get_employee');

    // AJAX SEARCH EMPLOYEE TO SELECT
    Route::get('/search_employee', 'Employee\Employee@search_employee');
    Route::get('/search_employee_2', 'Employee\Employee@search_employee_2');

    // Change pass
    Route::post('/change_pass', 'User\Users@change_pass')->name('change_pass');

    // Memo
    Route::get('/memo', 'Memo\MemoController@memo')->name('memo');
    Route::post('/add_memo', 'Memo\MemoController@add_memo');
    Route::get('/memo-noticed','Memo\MemoController@noticed_by');

    // ajax
    Route::post('/memo/noticed-by-employees', 'Memo\MemoController@noticed_by_employees');
    Route::post('/memo/outlets', 'Memo\MemoController@view_outlets');

    // DOWNLOAD MEMO
    Route::get('memo/download/{file_name}', function($file_name = null) {
        $path = storage_path().'/'.'uploads'.'/memo/'.$file_name;
        if (file_exists($path)) return Response::download($path);
    });

    Route::get('memo/delete/{id}', 'Memo\MemoController@deleteMemo')->name('delete-memo');

    // Notices
    Route::get('/notices', 'Notice\NoticeController@notices')->name('notices');
    Route::post('/add_notice', 'Notice\NoticeController@add_notice');
    Route::get('/notice-noticed','Notice\NoticeController@noticed_by');

    // DOWNLOAD Notice
    Route::get('notice/download/{file_name}', function($file_name = null) {
        $path = storage_path().'/'.'uploads'.'/notice/'.$file_name;
        if (file_exists($path)) return Response::download($path);
    });

    Route::get('notice/delete/{id}', 'Notice\NoticeController@deleteNotice')->name('delete-notice');


    // VEHICLES
    Route::get('/trip_details', 'Vehicles\TripsController@trip_details')->name('trip_details');
    Route::post('/add_new_trip', 'Vehicles\TripsController@add_new_trip')->name('add_new_trip');
    Route::get('/vehicle_details', 'Vehicles\VehiclesController@vehicle_details')->name('vehicle_details');
    Route::post('/add_new_vehicle', 'Vehicles\VehiclesController@add_new_vehicle')->name('add_new_vehicle');
    Route::post('/vehicles', 'Vehicles\VehiclesController@store');

    // SETTINGS
    Route::get('/settings', 'Settings\SettingsController@settings')->name('settings');
    // SET QUESTION
    Route::post('/set-question', 'Settings\SettingsController@set_question')->name('set_question');
    // SET PIN
    Route::post('/set-pin', 'Settings\SettingsController@set_pin')->name('set_pin');
    // CHANGE PIN
    Route::post('/change-pin', 'Settings\SettingsController@change_pin')->name('change_pin');
    // Lock module
    Route::post('/page-lock', 'Settings\SettingsController@page_lock')->name('page_lock');

    // page lock access
    Route::post('/page-lock-access', 'Settings\SettingsController@page_lock_access')->name('page_lock_access');

    // system lock
    Route::post('/save-system-lock', 'Settings\SettingsController@save_system_lock')->name('save-system-lock');



});


Route::get('/applicant', 'Applicant\ApplicantController@index');
Route::post('/applicant', 'Applicant\ApplicantController@save')->name('applicant');

//LOGIN
Route::get('/login', function () {
    if (Session::has('logged')) {return redirect('/dashboard');}
    return view('Pages.login');
})->name('login');
//POST LOGIN
Route::post('/login', 'Login@login_process')->name('login');

// LOGIN BP
Route::get('/bbb', function () {
    if (Session::has('logged')) {return redirect('/dashboard');}
    return view('Pages.login_2');
})->name('login_2');
