<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('employee', 'EmployeeCrudController');
    Route::crud('civilstatus', 'CivilStatusCrudController');
    Route::crud('gender', 'GenderCrudController');
    Route::crud('bloodtype', 'BloodTypeCrudController');
    Route::crud('citizenship', 'CitizenshipCrudController');
    Route::crud('religion', 'ReligionCrudController');
    Route::crud('audittrail', 'AuditTrailCrudController');
    Route::crud('governmentexamination', 'GovernmentExaminationCrudController');
    Route::crud('supportingdocument', 'SupportingDocumentCrudController');
    Route::crud('awardandrecognition', 'AwardAndRecognitionCrudController');
    Route::crud('workexperience', 'WorkExperienceCrudController');
    Route::crud('trainingandseminar', 'TrainingAndSeminarCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('familydata', 'FamilyDataCrudController');
    Route::crud('relation', 'RelationCrudController');
    Route::crud('educationallevel', 'EducationalLevelCrudController');
    Route::crud('educationalbackground', 'EducationalBackgroundCrudController');
    Route::crud('medicalinformation', 'MedicalInformationCrudController');
    Route::crud('professionalorg', 'ProfessionalOrgCrudController');
    Route::crud('beneficiary', 'BeneficiaryCrudController');
    Route::crud('dependents', 'DependentsCrudController');
    Route::crud('skillandtalent', 'SkillAndTalentCrudController');
    Route::crud('paymentmethod', 'PaymentMethodCrudController');
    Route::crud('paybasis', 'PayBasisCrudController');
    Route::crud('jobstatus', 'JobStatusCrudController');
    Route::crud('employmentstatus', 'EmploymentStatusCrudController');
    Route::crud('company', 'CompanyCrudController');
    Route::crud('location', 'LocationCrudController');
    Route::crud('department', 'DepartmentCrudController');
    Route::crud('division', 'DivisionCrudController');
    Route::crud('section', 'SectionCrudController');
    Route::crud('position', 'PositionCrudController');
    Route::crud('level', 'LevelCrudController');
    Route::crud('rank', 'RankCrudController');
    Route::crud('grouping', 'GroupingCrudController');
    Route::crud('daysperyear', 'DaysPerYearCrudController');
    Route::crud('employmentinformation', 'EmploymentInformationCrudController');
    Route::crud('employmentinfofield', 'EmploymentInfoFieldCrudController');
    Route::crud('performanceappraisal', 'PerformanceAppraisalCrudController');
    Route::crud('appraisaltype', 'AppraisalTypeCrudController');
    Route::crud('appraisalinterpretation', 'AppraisalInterpretationCrudController');
    Route::crud('offenceclassification', 'OffenceClassificationCrudController');
    Route::crud('gravityofsanction', 'GravityOfSanctionCrudController');
    Route::crud('offenceandsanction', 'OffenceAndSanctionCrudController');
    Route::crud('shiftschedules', 'ShiftSchedulesCrudController');
    Route::crud('employeeshiftschedule', 'EmployeeShiftScheduleCrudController');
    Route::crud('changeshiftschedule', 'ChangeShiftScheduleCrudController');
    Route::crud('holiday', 'HolidayCrudController');
    Route::crud('dtrlogs', 'DtrLogsCrudController');
    Route::crud('employeetimeclock', 'EmployeeTimeClockCrudController');
    Route::crud('team', 'TeamCrudController');
    Route::crud('payrollperiod', 'PayrollPeriodCrudController');
    Route::crud('withholdingtaxbasis', 'WithholdingTaxBasisCrudController');
    Route::crud('leavetype', 'LeaveTypeCrudController');
    Route::crud('leavecredit', 'LeaveCreditCrudController');
    Route::crud('leaveapplication', 'LeaveApplicationCrudController');
    Route::crud('leaveapprover', 'LeaveApproverCrudController');
    Route::crud('dailytimerecord', 'DailyTimeRecordCrudController');
}); // this should be the absolute last line of this file