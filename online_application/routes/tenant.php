<?php

use App\Http\Controllers\Tenant;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Tenant\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\School;
use App\Http\Controllers\Tenant\Plugins;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Tenant\Auth\Agents;
use App\Http\Controllers\Tenant\School\Agent;
use App\Http\Controllers\SalesContactController;
use App\Http\Controllers\Tenant\Auth\Instructors;
use App\Http\Controllers\Tenant\School\Instructor;


Route::middleware('auth')->group(function () {
    Route::get('/thankyou', function () {
        return view('thankyou');
    });

    Route::get('/usersettings', [UserController::class, 'getSettings'])->name('user.settings.get');

    Route::put('/usersettings', [UserController::class, 'updateSettings'])->name('user.settings.put');

    Route::get('/dashboard', [Tenant\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/{widget}', [Tenant\DashboardController::class, 'getWidget'])->name('dashboard.widget');

    // Document Builder
    Route::get('/documents/share/{student}', [Tenant\DocumentBuilderController::class, 'showShareDocument'])->name('document.share.show');
    Route::post('/documents/share', [Tenant\DocumentBuilderController::class, 'shareDocument'])->name('document.share');
    Route::delete('document-builder/bulk-delete', [Tenant\DocumentBuilderController::class, 'bulkDestroy'])->name('documentBuilder.bulk.destroy');
    Route::post('document-builder/pdf-infos', [Tenant\DocumentBuilderController::class, 'getPdfInfos'])->name('documentBuilder.pdfInfo');
    Route::get('document-builder/{documentBuilder}/build/{id}', [Tenant\DocumentBuilderController::class, 'buildDocument'])->name('documentBuilder.build');
    Route::resource('documentBuilder', Tenant\DocumentBuilderController::class);
    Route::resource('documents', Tenant\ShareableController::class);

    // Reports (CRUD)
    Route::resource('reports', Tenant\ReportController::class);
    Route::get('reports/table-columns', [Tenant\ReportController::class, 'getTableColumns'])->name('reports.table-columns');
    Route::get('reports/{report}/filters', [Tenant\ReportController::class, 'getReportFilter'])->name('reports.filters');
    Route::get('reports/attendance/{student}', [Tenant\ReportController::class, 'getStudentAttendanceReport'])->name('reports.attendance');
    Route::delete('reports/bulk-delete', [Tenant\ReportController::class, 'bulkDestroy'])->name('reports.bulk.destroy');
    Route::get('reports/{report}/download/excel', [Tenant\ReportController::class, 'reportsDownloadExcel'])
        ->name('reports.download.excel');

    // Agencies Resource (CRUD)
    Route::resource('agencies', Tenant\AgencyController::class);
    // Toggle Status
    Route::post('/agencies/{agency}/status', [Tenant\AgencyController::class, 'status'])->name('agencies.status');

    // Applications Resource (CRUD)
    Route::resource('applications', Tenant\ApplicationController::class);
    //
    Route::get('applications/{application}/build', [Tenant\ApplicationController::class, 'build'])->name('applications.build');

    Route::resource('promocodes', Tenant\PromocodeController::class);

    Route::resource('customfields', Tenant\CustomfieldController::class);

    Route::get('import/upload', [Tenant\ImportController::class, 'upload'])->name('import.upload');

    Route::post('import/upload', [Tenant\ImportController::class, 'upload'])->name('import.upload');

    Route::post('import/import', [Tenant\ImportController::class, 'import'])->name('import.import');

    Route::resource('semesters', Tenant\SemesterController::class);

    Route::get('roles/{school}/{user}/update', [Tenant\UserRolesController::class, 'userRolesEdit'])
        ->name('user.roles.edit');

    Route::put('roles/{school}/{user}/update', [Tenant\UserRolesController::class, 'userRolesUpdate'])
        ->name('user.roles.update');

    Route::resource('roles', Tenant\RoleController::class);

    // API Key
    Route::get(
        'api-key',
        [Tenant\ApiKeyController::class, 'show']
    )->name('api.key.show');



    // Application Action Create
    /* Route::get(
    'applications/{application}/actions/{action_type}', [Tenant\ApplicationActionController::class, 'create']
    )->name('application.actions.create'); */

    Route::get(
        'applications/{application}/actions',
        [Tenant\ApplicationActionController::class, 'create']
    )->name('application.actions.create');

    Route::post(
        'applications/{application}/actions',
        [Tenant\ApplicationActionController::class, 'store']
    )->name('application.actions.store');

    Route::get(
        'applications/{application}/actions/{action}',
        [Tenant\ApplicationActionController::class, 'edit']
    )->name('application.actions.edit');

    Route::put(
        'applications/{application}/actions/{action}',
        [Tenant\ApplicationActionController::class, 'update']
    )->name('application.actions.update');

    Route::delete(
        'applications/{application}/actions/{action}',
        [Tenant\ApplicationActionController::class, 'destroy']
    )->name('application.actions.destroy');

//    Application Cloning
    Route::get('applications/{application}/clone', [Tenant\ApplicationCloneController::class, 'create'])
        ->name('application.cloning.create');
    Route::post('applications/{application}/clone', [Tenant\ApplicationCloneController::class, 'store'])
        ->name('application.cloning.store');

    // Fields Resource (CRUD)
    Route::resource('fields', Tenant\FieldController::class);

    Route::post('fields/{old_field}/{section}/{application}/clone', [Tenant\FieldController::class, 'clone'])
        ->name('fields.clone');

    // Sections Resource (CRUD)
    Route::resource('sections', Tenant\SectionController::class);

    Route::post('sections/{old_section}/{application}/clone', [Tenant\SectionController::class, 'clone'])
        ->name('sections.clone');

    //Campuses Resoutce (CRUD)
    Route::resource('campuses', Tenant\CampusController::class);

    //Classrooms Resoutce (CRUD)
    Route::resource('classrooms', Tenant\ClassroomController::class);

    Route::get('classroomSlots/classrooms', [Tenant\ClassroomSlotController::class, 'getClassrooms'])
        ->name('classroomSlots.classrooms');
    Route::get('classroomSlots/classroomSlots', [Tenant\ClassroomSlotController::class, 'getClassroomSlots'])
        ->name('classroomSlots.classroomSlots');

    //Group Resoutce (CRUD)
    Route::resource('groups', Tenant\GroupController::class);
    Route::get('group/{group}/students', [Tenant\GroupController::class, 'groupStudents'])->name('get.group.students');
    Route::get('group/{group}/students/{student}/{place?}', [Tenant\GroupController::class, 'groupStudent'])->name('group.student.show');
    Route::get('group/{group}/courses', [Tenant\GroupController::class, 'groupCourses'])->name('get.group.courses');
    Route::get('group/{group}/courses/{course}/{place?}', [Tenant\GroupController::class, 'groupCourse'])->name('group.course.show');
    Route::get('group/{group}/lessons', [Tenant\GroupController::class, 'groupLessons'])->name('get.group.lessons');
    Route::get('group/{group}/lessons/{lesson}/{place?}', [Tenant\GroupController::class, 'groupLesson'])->name('group.lesson.show');

    //Modules Resoutce (CRUD)
    Route::resource('modules', Tenant\ModuleController::class);

    Route::get('lessons/bulk-edit', [Tenant\LessonController::class, 'bulkEdit'])->name('lessons.bulk.edit');

    Route::post('lessons/bulk-edit', [Tenant\LessonController::class, 'bulkUpdate'])->name('lessons.bulk.update');

    Route::delete('lessons/bulk-delete', [Tenant\LessonController::class, 'bulkDestroy'])->name('lessons.bulk.destroy');


    //Lesson Resoutce (CRUD)
    Route::get('lessons/getLessons', [Tenant\LessonController::class, 'getLessons'])
        ->name('lessons.getLessons');

    Route::get('lessons/select/excel', [Tenant\LessonController::class, 'lessonDownloadIndex'])
        ->name('lessons.select.excel');

    Route::resource('lessons', Tenant\LessonController::class);

    Route::get('lessons/{lesson}/exportAttendances', [Tenant\LessonController::class, 'lessonAttendancesExport'])->name('export.lesson.attendances');
    Route::post('lessons/{lesson}/updateAttendances', [Tenant\LessonController::class, 'lessonAttendancesUpdate'])->name('update.lesson.attendances');


    Route::get('lessons/multi/create', [Tenant\LessonController::class, 'createMulti'])->name('lessons.createMulti');
    Route::get('lessons/{group}/create', [Tenant\LessonController::class, 'createWithGroup'])->name('lessons.createWithGroup');
    Route::get('lessons/{classroom}/createWithClassroom', [Tenant\LessonController::class, 'createWithClassroom'])
        ->name('lessons.createWithClassroom');

    Route::get('lessons/{group}/multi/create', [Tenant\LessonController::class, 'createMultiWithGroup'])
        ->name('lessons.createMultiWithGroup');
    Route::get('lessons/{classroom}/multi/ccreateWithClassroom', [Tenant\LessonController::class, 'createMultiWithClassroom'])
        ->name('lessons.createMultiWithClassroom');

    Route::post('lessons/multi/store', [Tenant\LessonController::class, 'storeMulti'])
        ->name('lessons.store.multi');

    Route::post('lessons/update/{lesson}', [Tenant\LessonController::class, 'update'])
        ->name('lessons.update');

    //Programs Resoutce (CRUD)
    Route::resource('programs', Tenant\ProgramController::class);
    Route::get('programs/el/schedule', [Tenant\CourseController::class, 'getCalendarSchedule'])->name('schedule.create');
//    Route::get('programs/{program}/schedule', [Tenant\CourseController::class, 'getCalendarSchedule'])->name('schedule.create');

    Route::resource('courses', Tenant\CourseController::class);
    Route::get('courses/{course}/information', [Tenant\CourseController::class, 'information'])->name('course.information');

    Route::post('courses/{course}/properties', [Tenant\CourseController::class, 'saveCourseProperties'])
        ->name('courses.properties');

    Route::get('courses/{course}/dates', [Tenant\CourseController::class, 'showCoursesDateForm'])
        ->name('course.date.create');

    //Route::get('courses/{course}/schedule', [Tenant\CourseController::class, 'getCalendarSchedule'])->name('schedule.create');
    //Route::get('courses/schedule', [Tenant\CourseController::class, 'getCalendarSchedule'])->name('schedule.creating');

    // Course Dates CRUD
    Route::get('courses/{course}/dates', [Tenant\DateController::class, 'create'])
        ->name('date.create');

    Route::post('courses/{course}/dates', [Tenant\DateController::class, 'store'])
        ->name('date.store');

    Route::delete('courses/{course}/dates/{date}', [Tenant\DateController::class, 'destroy'])
        ->name('date.delete');

    Route::get('courses/{course}/dates/{date}', [Tenant\DateController::class, 'edit'])
        ->name('date.edit');

    Route::post('courses/{course}/dates/{date}', [Tenant\DateController::class, 'update'])
        ->name('date.update');

    //Date Addon CRUD
    Route::delete('courses/{course}/dates/{date}/addon', [Tenant\DateAddonController::class, 'destroy'])
        ->name('date.addon.delete');

    Route::get('courses/{course}/dates/{date}/addon', [Tenant\DateAddonController::class, 'edit'])
        ->name('date.addon.edit');

    Route::post('courses/{course}/dates/{date}/addon', [Tenant\DateAddonController::class, 'update'])
        ->name('date.addon.update');

    // Course Addons CRUD
    Route::get('courses/{course}/addons', [Tenant\AddonController::class, 'create'])
        ->name('addon.create');

    Route::post('courses/{course}/addons', [Tenant\AddonController::class, 'store'])
        ->name('addon.store');

    Route::get('courses/{course}/addons/{addon}', [Tenant\AddonController::class, 'edit'])
        ->name('addon.edit');

    Route::post('courses/{course}/addons/{addon}', [Tenant\AddonController::class, 'update'])
        ->name('addon.update');

    Route::delete('courses/{course}/addons/{addon}', [Tenant\AddonController::class, 'destroy'])
        ->name('addon.delete');

    Route::get('students/download/excel', [Tenant\StudentEnrolledController::class, 'studentsDownloadExcel'])
        ->name('students.download.excel');

    Route::delete('students/bulk-delete', [Tenant\StudentController::class, 'bulkDestroy'])->name('students.bulk.destroy');

    // Students enrolled Resource (CRUD)
    Route::get('students/{student}/quick-edit', [Tenant\StudentController::class, 'getDataSource'])->name('student.quick-edit.source');

    Route::post('students/{student}/quick-edit', [Tenant\StudentController::class, 'quickEdit'])->name('student.quick-edit.update');

    Route::delete('students/stop-important', [Tenant\StudentController::class, 'stopImportsonating'])->name('school.user.student.destroy');

    Route::get('students/{student}/impersonate', [Tenant\StudentController::class, 'impersonateStudent'])->name('student.impersonate');

    Route::get('students/enrolled/list', [Tenant\StudentEnrolledController::class, 'list'])
        ->name('students.enrolled.list');

    Route::get('students/enrolled', [Tenant\StudentEnrolledController::class, 'index'])
        ->name('students.enrolled.index');

    // Students Resource (CRUD)
    Route::resource('students', Tenant\StudentController::class);

    Route::get('submissions/applicants/{student}', [Tenant\StudentController::class, 'showApplicant'])
        ->name('applicants.show');

    Route::get(
        'students/{student}/{invoice}/email',
        [Tenant\StudentController::class, 'showReminderEmailForm']
    )->name('invoice.reminder.email');

    Route::post(
        'students/{student}/{invoice}/email',
        [Tenant\StudentController::class, 'sendReminderEmail']
    )->name('invoice.reminder.email.send');

    Route::get('leads', [Tenant\BuilderAssistantController::class, 'listAssistantsRequestedByMailWithoutApplication'])
        ->name('students.leads');

    Route::get('contacts/search', [Tenant\StudentController::class, 'searchContacts'])
        ->name('contacts.search');

    Route::delete(
        'leads/{lead}/{type}',
        [Tenant\BuilderAssistantController::class, 'destroyAssistantsRequestedByMailWithoutApplication']
    )->name('students.leads.destroy');

    Route::get('applicants', [Tenant\QuotationsController::class, 'listAssistantsRequestedByMailWithoutApplication'])
        ->name('students.applicants');

    Route::get('students/json/show', [Tenant\StudentController::class, 'showJson'])
        ->name('students.showJson');

    Route::get('students/json/paymentShow', [Tenant\StudentController::class, 'showPaymentJson'])
        ->name('students.showPaymentJson');

    Route::get('/accounting', [Tenant\AccountingController::class, 'index'])->name('accounting.index');

    Route::get('accounting/getInvoicePayment', [Tenant\AccountingController::class, 'getInvoicePayment'])
        ->name('accounting.getInvoicePayment');

    Route::get('submissions', [Tenant\SubmissionController::class, 'index'])->name('submissions.index');


    Route::get('submissions/new', [Tenant\SubmissionController::class, 'create'])->name('submissions.add.new');
    Route::post('submissions/store', [Tenant\SubmissionController::class, 'store'])->name('submissions.store.new');

    Route::get('submissions/getSubmissions', [Tenant\SubmissionController::class, 'getSubmissions'])
        ->name('submissions.getSubmissions');

    Route::delete('submissions/destroy/{submission}', [Tenant\SubmissionController::class, 'destroy'])
        ->name('submissions.destroy');

    Route::get('submissions/completions', [Tenant\SubmissionController::class, 'completions'])->name('submissions.completions');
    Route::get('submissions/percentageCompletions', [Tenant\SubmissionController::class, 'percentageCompletions'])->name('submissions.percentageCompletions');
    Route::get('submissions/totalCompletions', [Tenant\SubmissionController::class, 'totalCompletions'])->name('submissions.total.completions');


    Route::get('submissions/bulk-edit', [Tenant\SubmissionController::class, 'bulkEdit'])->name('submissions.bulk.edit');

    Route::post('submissions/bulk-edit', [Tenant\SubmissionController::class, 'bulkUpdate'])->name('submissions.bulk.update');

    Route::delete('submissions/bulk-delete', [Tenant\SubmissionController::class, 'bulkDestroy'])->name('submissions.bulk.destroy');

    Route::post('submissions/bulk-archive', [Tenant\SubmissionController::class, 'bulkArchive'])->name('submissions.bulk.archive');

    Route::post('submissions/bulk-unarchive', [Tenant\SubmissionController::class, 'bulkUnarchive'])->name('submissions.bulk.unarchive');


    Route::get('submissions/{submission}', [Tenant\SubmissionController::class, 'show'])->name('submissions.show');

    Route::get('submissions/{submission}/status', [Tenant\ApplicationStatusController::class, 'submissionStatusEdit'])
        ->name('application.status.edit');

    Route::put('submissions/{submission}/status', [Tenant\ApplicationStatusController::class, 'submissionStatusUpdate'])
        ->name('application.status.update');

    Route::get('submissions/{submission}/activate', [Tenant\ApplicationStatusController::class, 'submissionStatusActivate'])
        ->name('application.status.activate');

	Route::post('submissions/{submission}/status', [Tenant\ApplicationStatusController::class, 'submissionStatusUpdatePost'])
        ->name('application.status.updatepost');

    Route::get('submissions/{submission}/status-list', [Tenant\ApplicationStatusController::class, 'getStatusList'])
        ->name('application.status.list');

    Route::get('submissions/select/excel', [Tenant\SubmissionController::class, 'submissionsDownloadIndex'])
        ->name('submissions.select.excel');

    Route::get('submissions/download/excel', [Tenant\SubmissionController::class, 'submissionsDownloadExcel'])
        ->name('submissions.download.excel');

    // Settings Resource (CRUD)
    Route::resource('settings', Tenant\SettingController::class);
    Route::post('/settings/addSchedule', [Tenant\SettingController::class, 'addScheduleOutsideSettings'])->name('settings.addSchedule');

    Route::post('settings/addNewSchedule', [Tenant\SettingController::class, 'addNewSchedule'])->name('settings.addNewSchedule');

    Route::post('settings/store-degrees', [Tenant\SettingController::class, 'storeDegrees'])->name('settings.storeDegrees');



    Route::delete('settings/deleteSchedule/{id}', [Tenant\SettingController::class, 'deleteSchedule'])->name('settings.deleteSchedule');

    // Application Status Resource (CRUD)
    Route::resource('applicationStatus', Tenant\ApplicationStatusController::class);

    // Quotations Resource (CRUD)
    Route::resource('quotations', Tenant\QuotationsController::class);

    // Assistant Resource (CRUD)
    Route::resource('assistantsBuilder', Tenant\BuilderAssistantController::class);

    Route::resource('followups', Tenant\FollowUpsController::class);

    // Delete Student's file
    Route::delete('{school}/{student}/files/{file}', [Tenant\StudentFileController::class, 'destroy'])
    ->name('student.file.delete');

    // Edit Student's stage
    Route::get('students/{student}/stage', [Tenant\StudentStageController::class, 'edit'])
        ->name('students.stage.edit');

    // Edit Student's stage
    Route::put('students/{student}/stage', [Tenant\StudentStageController::class, 'update'])
        ->name('students.stage.update');

    // Review E-Signature Contract
    Route::get('students/{student}/review-contract', [Tenant\EnvelopeController::class, 'reviewContract'])
        ->name('students.contract.review');

    // Add Student's cohort
    Route::get('students/{student}/cohort', [Tenant\StudentController::class, 'createAddCohort'])
        ->name('students.create.cohort');

    // Add Student's cohort
    Route::put('students/{student}/cohort/add', [Tenant\StudentController::class, 'addToCohort'])
        ->name('students.add.cohort');

    // Add Student's cohort
    Route::delete('students/{student}/cohort/{group}/delete', [Tenant\StudentController::class, 'destroyFromCohort'])
        ->name('students.destroy.cohort');

    // Edit Student's uuid - number
    Route::get('students/{student}/uuid', [Tenant\StudentController::class, 'editUuid'])
        ->name('students.uuid.edit');

    // Edit Student's uuid - number
    Route::put('students/{student}/uuid', [Tenant\StudentController::class, 'updateUuid'])
        ->name('students.uuid.update');

    // // Export Student's to excel file
    // Route::get('students/download/excel', [Tenant\StudentController::class, 'studentsDownloadExcel'])
    // ->name('students.download.excel');

    /* Route::get('quotation', [Tenant\QuotationController::class, 'index'])->name('quotation.index');
    Route::post('quotation', [Tenant\QuotationController::class, 'store'])->name('quotation.store'); */

    // Download PDF
    Route::get('pdf/{submission}/{action}', [Tenant\PDFController::class, 'pdf'])->name('pdf.view');

    Route::get('application/{application}/pdf/{action}', [Tenant\PDFController::class, 'generateEmptyApplicationPDF'])->name('application.pdf');

    Route::get('export/{application}', [Tenant\ExportController::class, 'export'])->name('export.application');

    // Invoice PDF View and Download
    Route::get('invoice/pdf/{invoice}/{action}', [Tenant\InvoiceController::class, 'pdf'])->name('invoice.pdf.action');

    Route::get('payment/pdf/{payment}/{action}', [Tenant\PaymentController::class, 'pdf'])->name('payment.pdf.action');

    /* Route::get('payment' , [Tenant\PaymentController::class, 'create'])->name('payment.create');
    Route::post('payment' , [Tenant\PaymentController::class, 'store'])->name('payment.store'); */

    Route::resource('payments', Tenant\PaymentController::class);

    Route::resource('integration', Tenant\IntegrationController::class);

    Route::resource('admissions', Tenant\AdmissionsController::class);

    // Toggle Advisor Avialability
    Route::post('admissions/{admission}/toggle', [Tenant\AdmissionsController::class, 'toggle'])->name('admissions.toggle');



    // Plugins CRUD
    Route::get('plugins', [Tenant\PluginsController::class, 'index'])->name('plugins.index');

    Route::get('plugins/setup/{plugin}', [Tenant\PluginsController::class, 'setup'])->name('plugins.setup');

    Route::post('plugins/setup/{plugin}', [Tenant\PluginsController::class, 'store'])->name('plugins.store');

    Route::get('plugins/setup/{plugin}/auth', [Tenant\PluginsController::class, 'auth'])->name('plugins.auth');

    Route::post('plugins/auth/{plugin}', [Tenant\Plugins\DocusignController::class, 'auth'])->name('plugins.docusign.auth');




    Route::get(
        '{school}/submission/{application}/field-data/{field}',
        [Tenant\School\ApplicationController::class, 'getFieldData']
    )->name('submission.field.data');

    Route::delete('services/bulk-delete', [Tenant\ServicesController::class, 'bulkDestroy'])->name('services.bulk.destroy');
    Route::post('services/code-validator', [Tenant\ServicesController::class, 'codeValidator'])->name('educationalservice.code-validator');
    Route::resource('services', Tenant\ServicesController::class);
    Route::get('educationalservicecategories/list', [Tenant\EducationalServiceCategoriesController::class, 'list'])->name('educationalservicecategories.list');
    Route::resource('educationalservicecategories', Tenant\EducationalServiceCategoriesController::class);
    Route::get('steps/progress/status', [Tenant\SubmissionController::class, 'calculateStepsProgressStatus']);

    //Sales Contact
    Route::get('/sales/contact', [SalesContactController::class, 'index'])->name('sales.contact.index');
});

Route::post('submissions/review/{submission}', [Tenant\SubmissionController::class, 'review'])->name('submissions.review');

Route::get('submissions/{submission}/review/show', [Tenant\SubmissionController::class, 'showReview'])
    ->name('submissions.show.review');

Route::get('submissions/{submission}/contract/new', [Tenant\SubmissionController::class, 'generateContract'])
    ->name('submission.contract.generate');

Route::get('submissions/{submission}/contract/sent', [Tenant\SubmissionController::class, 'contractSent']);

Route::get('envelopes/', [Tenant\EnvelopeController::class, 'index'])
    ->name('envelope.index');

Route::get('envelopes/create', [Tenant\EnvelopeController::class, 'create'])
    ->name('envelope.create');


Route::post('envelopes/create', [Tenant\EnvelopeController::class, 'store'])
    ->name('envelope.store');

Route::get('envelopes/{envelope}/clone', [Tenant\EnvelopeController::class, 'clone'])
        ->name('envelope.clone');

Route::get('envelopes/add-template', [Tenant\EnvelopeController::class, 'showAddTemplateToEnvelope'])
    ->name('envelope.add.template');

Route::get('envelopes/{envelope}/edit-template/{template}', [Tenant\EnvelopeController::class, 'showEditTemplateForm'])
    ->name('envelope.edit.template');


Route::get('envelopes/add-signer', [Tenant\EnvelopeController::class, 'showAddSignerToEnvelope'])
    ->name('envelope.add.signer');


Route::get('envelopes/{envelope}/edit', [Tenant\EnvelopeController::class, 'edit'])
    ->name('envelope.edit');

Route::post('envelopes/{envelope}/edit', [Tenant\EnvelopeController::class, 'update']);

Route::get('envelopes/{envelope}', [Tenant\EnvelopeController::class, 'show'])
->name('envelope.show');

Route::delete('envelopes/{envelope}/delete', [Tenant\EnvelopeController::class, 'destroy'])
->name('envelope.destroy');

Route::get('contact/{contract}/list', [Tenant\ContractController::class, 'list'])
->name('contart.documents.list');

/* Messages CRUD */
Route::resource('messages', Tenant\MessageController::class);
Route::post('/messages/attachments/upload', [Tenant\MessageController::class, 'uploadAttachment'])
->name('messages.attachments.upload');

Route::post('/messages/attachments/delete', [Tenant\MessageController::class, 'deleteAttachment'])
->name('messages.attachments.delete');



Route::get('/students/{student}/envelopes/send', [Tenant\EnvelopeController::class, 'showSendEnvelopeForm'])
->name('envelope.send');

Route::post(
    '{school}/applications/{submission}/edit-field/{field}',
    [Tenant\School\ApplicationController::class, 'editField']
)->name('application.field.edit');

// School Auth
// Student Login

Route::post('{school}/ajax', [Tenant\School\AjaxController::class, 'excuate'])->name('front.ajax');

Route::get('{school}/login', [Tenant\Auth\LoginController::class, 'showLoginForm'])->name('school.login');
Route::post('{school}/login', [Tenant\Auth\LoginController::class, 'login']);

// Student Resgister
Route::get('{school}/register', [Tenant\Auth\RegisterController::class, 'showRegistrationForm'])->name('school.register');

Route::post('{school}/register', [Tenant\Auth\RegisterController::class, 'register']);

Route::get(
    '{school}/forgot-password',
    [Tenant\Auth\ForgotPasswordController::class, 'showLinkRequestForm']
)->name('school.forgot.password');
Route::post(
    '{school}/forgot-password',
    [Tenant\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']
);

Route::get('{school}/reset-password',
    [Tenant\Auth\ResetPasswordController::class, 'showResetForm']
)->name('school.reset.password');

Route::post('{school}/reset-password', [Tenant\Auth\ResetPasswordController::class, 'reset'])->name('school.reset.password');

// Agent Login
Route::get('{school}/agents/login', [Tenant\Auth\Agents\LoginController::class, 'showLoginForm'])->name('school.agent.login');

Route::post('{school}/agents/login', [Tenant\Auth\Agents\LoginController::class, 'login']);

Route::get(
    '{school}/agents/resend-activation',
    [Tenant\Auth\Agents\LoginController::class, 'showResendActivation']
)->name('school.agent.resend.activation');

Route::post(
    '{school}/agents/resend-activation',
    [Tenant\Auth\Agents\LoginController::class, 'resendActivation']
)->name('school.agent.resend.activation');

Route::post('{school}/agents/logout', [Tenant\Auth\Agents\LoginController::class, 'logout'])->name('school.agent.logout');

Route::get(
    '{school}/agents/forgot-password',
    [Tenant\Auth\Agents\ForgotPasswordController::class, 'showLinkRequestForm']
)->name('school.agent.forgot.password');
Route::post(
    '{school}/agents/forgot-password',
    [Tenant\Auth\Agents\ForgotPasswordController::class, 'sendResetLinkEmail']
);

Route::get(
    '{school}/agents/reset-password',
    [Tenant\Auth\Agents\ResetPasswordController::class, 'showResetForm']
)->name('school.agent.reset.password');
Route::post('{school}/agents/reset-password', [Tenant\Auth\Agents\ResetPasswordController::class, 'reset'])
    ->name('school.agent.reset.password');

// Agent Resgister
Route::get(
    '{school}/agents/register',
    [Tenant\Auth\Agents\RegisterController::class, 'showRegistrationForm']
)->name('school.agent.register');

Route::post('{school}/agents/register', [Tenant\Auth\Agents\RegisterController::class, 'register']);

Route::get(
    '{school}/agents/register/{agency}/step-2',
[Tenant\Auth\Agents\RegisterController::class, 'showRegistrationApplications']
)->name('school.agent.register.step2');

Route::post(
'{school}/agents/register/{agency}/step-2',
[Tenant\Auth\Agents\RegisterController::class, 'registrationApplicationSubmit']
)->name('school.agent.register.step2.submit');


Route::get(
    '{school}/agents/agency',
    [Tenant\Auth\Agents\RegisterController::class, 'showCreateAgencyForm']
)->name('school.agent.agency');

// Get Dynamic List of Agencies
Route::get(
    '{school}/agents/register/agencies',
    [Tenant\AgencyController::class, 'getAgenciesList']
)->name('school.agent.register.agencies');

Route::put(
    '/agencies/invite/Agents/{agency}',
    [Tenant\AgencyController::class, 'inviteAgents']
)->name('agencies.inviteAgents');

Route::post('agencies/bulk-delete', [Tenant\AgencyController::class, 'bulkDestroy'])->name('agencies.bulk.destroy');

// Export Agencies to excel file
Route::get('agencies/download/excel', [Tenant\AgencyController::class, 'agenciesDownloadExcel'])
->name('agencies.download.excel');

//Agent Account Activation
Route::get(
    '{school}/agents/activate',
    [Tenant\Auth\Agents\ActivationController::class, 'activate']
)->name('school.agent.activate');

// Instructor Login
Route::get('{school}/instructors/login', [Tenant\Auth\Instructors\LoginController::class, 'showLoginForm'])
    ->name('school.instructor.login');

Route::post('{school}/instructors/login', [Tenant\Auth\Instructors\LoginController::class, 'login']);

Route::get(
    '{school}/instructors/resend-activation',
    [Tenant\Auth\Instructors\LoginController::class, 'showResendActivation']
)->name('school.instructor.resend.activation');

Route::post(
    '{school}/instructors/resend-activation',
    [Tenant\Auth\Instructors\LoginController::class, 'resendActivation']
)->name('school.instructor.resend.activation');

Route::post('{school}/instructors/logout', [Tenant\Auth\Instructors\LoginController::class, 'logout'])
    ->name('school.instructor.logout');



Route::get(
    '{school}/instructors/forgot-password',
    [Tenant\Auth\Instructors\ForgotPasswordController::class, 'showLinkRequestForm']
)->name('school.instructor.forgot.password');
Route::post(
    '{school}/instructors/forgot-password',
    [Tenant\Auth\Instructors\ForgotPasswordController::class, 'sendResetLinkEmail']
);

Route::get(
    '{school}/instructors/reset-password',
    [Tenant\Auth\Instructors\ResetPasswordController::class, 'showResetForm']
)->name('school.instructor.reset.password');
Route::post('{school}/instructors/reset-password', [Tenant\Auth\Instructors\ResetPasswordController::class, 'reset'])
    ->name('school.instructor.reset.password');

// Instructor Register
Route::get(
    '{school}/instructors/register',
    [Tenant\Auth\Instructors\RegisterController::class, 'showRegistrationForm']
)->name('school.instructor.register');
Route::post('{school}/instructors/register', [Tenant\Auth\Instructors\RegisterController::class, 'register']);

// Authorized Agents Only
Route::middleware('agentsAuth')->group(function () {

    Route::post('{school}/agents/ajax', [Tenant\School\Agent\AjaxController::class, 'excuate'])->name('agents.ajax');



    Route::get('{school}/agents', [Tenant\School\Agent\HomeController::class, 'index'])->name('school.agent.home');

    Route::get('{school}/agents/finance', [Tenant\School\Agent\AccountingController::class, 'finance'])->name('school.agent.finance');
    Route::get('{school}/agents/getInvoicePayment', [Tenant\School\Agent\AccountingController::class, 'getInvoicePayment'])
        ->name('school.agent.getInvoicePayment');

    Route::get('{school}/agents/submissions', [Tenant\School\Agent\HomeController::class, 'submissions'])
        ->name('school.agent.submissions');

    Route::get('{school}/agents/getSubmissions', [Tenant\School\Agent\HomeController::class, 'getSubmissions'])
        ->name('school.agent.getSubmissions');

    Route::get('{school}/agents/invoice/pdf/{invoice}/{action}', [Tenant\School\Agent\AccountingController::class, 'invoicePdf'])
        ->name('school.agent.invoice.pdf.action');
    Route::get('{school}/agents/payment/pdf/{payment}/{action}', [Tenant\School\Agent\AccountingController::class, 'paymentPdf'])
        ->name('school.agent.payment.pdf.action');

    /* Route::get('{school}/agents/applicants',
    [Tenant\School\Agent\HomeController::class, 'applicants'])->name('school.agent.applicants'); */

    Route::get('{school}/agents/account', [Tenant\School\Agent\AgentController::class, 'edit'])->name('school.agent.edit');

    Route::post(
        '{school}/agents/account',
        [Tenant\School\Agent\AgentController::class, 'update']
    )->name('school.agent.update');

    // Update agency inforamtion and invite agents
    Route::get(
        '{school}/agents/agency/{agency}',
        [Tenant\School\Agent\AgentAgencyController::class, 'edit']
    )->name('school.agent.agency.edit');

    Route::post(
        '{school}/agents/agency/{agency}/update',
        [Tenant\School\Agent\AgentAgencyController::class, 'update']
    )->name('school.agent.agency.update');

    Route::post(
        '{school}/agents/agency/{agency}/rolPrivileges',
        [Tenant\School\Agent\AgentAgencyController::class, 'rolPrivileges']
    )->name('school.agent.agency.rolPrivileges');

    Route::get(
        '{school}/agents/student/create',
        [Tenant\School\Agent\ImpersonatController::class, 'create']
    )->name('school.agent.student.create');

    Route::get(
        '{school}/agents/booking/student/bookingCreate',
        [Tenant\School\Agent\ImpersonatController::class, 'bookingCreate']
    )->name('school.agent.booking.student.create');

    Route::post(
        '{school}/agents/student/create',
        [Tenant\School\Agent\ImpersonatController::class, 'Store']
    )->name('school.agent.student.store');

    Route::post(
        '{school}/agents/student/bookingCreate',
        [Tenant\School\Agent\ImpersonatController::class, 'bookingStore']
    )->name('school.agent.booking.student.store');

    Route::delete(
        '{school}/agents/student/impersonate/stop',
        [Tenant\School\Agent\ImpersonatController::class, 'destroy']
    )->name('school.agent.student.destroy');

    Route::get(
        '{school}/agents/students/{student}',
        [Tenant\StudentController::class, 'showInAgent']
    )->name('agent.student.show');

    Route::get(
        '{school}/agents/{application}/{student}/submit',
        [Tenant\School\Agent\ImpersonatController::class, 'submitSudentApplication']
    )->name('school.agent.student.submit');

    // Download PDF
    Route::get(
        '{school}/pdf/{submission}/{action}',
        [Tenant\School\Agent\PDFController::class, 'pdf']
    )->name('agent.pdf.download');

    Route::post(
        '{school}/agents/{agency}/application/{application}',
        [Tenant\School\Agent\AgencySubmissionsController::class, 'submit']
    )->name('school.agency.application.submit');
});

// Authorized Instructors Only
Route::middleware('instructorsAuth')->group(function () {
    Route::get('{school}/instructor', [Tenant\School\Instructor\HomeController::class, 'index'])->name('school.instructor.home');

    Route::get('{school}/instructors/attendance', [Tenant\AttendanceController::class, 'index'])->name('attendances.index');

    Route::get('{school}/instructors/getLessons', [Tenant\AttendanceController::class, 'getLessons'])
        ->name('attendances.getLessons');

    Route::get(
        '{school}/instructors/attendance/{lesson}',
        [Tenant\AttendanceController::class, 'create']
    )->name('attendances.create');

    Route::post(
        '{school}/instructors/attendance/{lesson}',
        [Tenant\AttendanceController::class, 'store']
    )->name('attendances.store');

    Route::get('{school}/instructors/calendar', [Tenant\School\Instructor\CalendarController::class, 'index'])
        ->name('instructor.calendar');

    Route::get('{school}/instructor/lessons/{course?}', [Tenant\School\Instructor\LessonsController::class, 'index'])->name('instructor.lessons');

    Route::get('{school}/instructor/lesson/{lesson}/attendances/{action}', [Tenant\School\Instructor\LessonsController::class, 'attendances'])->name('instructor.lesson.attendances');

    Route::post('{school}/instructor/lesson/{lesson}/attendances/update', [Tenant\School\Instructor\LessonsController::class, 'updateAttendances'])->name('instructor.update.attendances');

    Route::get('{school}/instructor/course/{course}/{place?}/{lesson?}', [Tenant\School\Instructor\CoursesController::class, 'show'])->name('instructor.course.show');

    Route::get('{school}/instructor/students/{student}/{course}/{place?}', [Tenant\School\Instructor\StudentsController::class, 'show'])->name('instructor.student.show');

    Route::get('{school}/instructor/profile', [Tenant\School\Instructor\HomeController::class, 'profile'])->name('instructor.profile');

    Route::post('{school}/instructor/profile/update', [Tenant\School\Instructor\HomeController::class, 'update'])->name('instructor.profile.update');

});

Route::get(
    'attendance/{attendance}/edit',
    [Tenant\AttendanceController::class, 'edit']
)->name('attendances.edit');

Route::post(
    'attendance/{attendance}/update',
    [Tenant\AttendanceController::class, 'update']
)->name('attendances.update');

//Back End Attendance Routes

Route::get('attendance', [Tenant\AttendanceController::class, 'showBackIndex'])
	->name('attendances.back.index');

Route::get('attendance/add-new',[Tenant\AttendanceController::class, 'addNew'])
	->name('attendances.add.new');

Route::post('attendance/add-new',[Tenant\AttendanceController::class, 'storeNew'])
	->name('attendances.store.new');

Route::get('attendances/getAttendances', [Tenant\AttendanceController::class, 'getAttendances'])
        ->name('attendances.getAttendances');

Route::get('attendance/bulk-edit', [Tenant\AttendanceController::class, 'bulkEdit'])
	->name('attendances.bulk.edit');

Route::post('attendance/bulk-edit', [Tenant\AttendanceController::class, 'bulkUpdate'])
	->name('attendances.bulk.update');

Route::delete('attendance/bulk-delete', [Tenant\AttendanceController::class, 'bulkDestroy'])
	->name('attendances.bulk.destroy');

Route::get('attendances/select/excel', [Tenant\AttendanceController::class, 'attendanceDownloadIndex'])
        ->name('attendances.select.excel');

Route::get('attendance/{attendance}/edit-attendance', [Tenant\AttendanceController::class, 'backEdit'])
	->name('attendances.back.edit');

Route::post('attendance/{attendance}/edit-attendance', [Tenant\AttendanceController::class, 'backUpdate'])
        ->name('attendances.back.update');

Route::delete('attendance/{id}', [Tenant\AttendanceController::class, 'backDestroy'])
	->name('attendances.back.destroy');


// update Or Create lesson Attendances

Route::get('{lesson}/attendance/{student}/edit', [Tenant\LessonAttendancesController::class, 'edit'])
        ->name('lesson.attendance.edit');

Route::post('{lesson}/attendance/{student}/edit', [Tenant\LessonAttendancesController::class, 'update'])
        ->name('lesson.attendance.update');

Route::get('{lesson}/attendances/bulk-edit', [Tenant\LessonAttendancesController::class, 'bulkEdit'])
        ->name('lesson.attendances.bulk.edit');

Route::post('{lesson}/attendances/bulk-edit', [Tenant\LessonAttendancesController::class, 'bulkUpdate'])
        ->name('lesson.attendances.bulk.update');

Route::post('{lesson}/attendances/update', [Tenant\LessonAttendancesController::class, 'updateOrCreateAttendances'])
        ->name('lesson.update.or.create.attendances');


//Instructor Resoutce (CRUD)

Route::get('instructors/{instructor}/impersonate', [Tenant\InstructorController::class, 'impersonate'])
    ->name('instructors.impersonate');

Route::post('instructors/{instructor}/stop-impersonate', [Tenant\InstructorController::class, 'stopImpersonate'])
    ->name('instructors.stop.impersonate');

Route::resource('instructors', Tenant\InstructorController::class);

Route::get(
    '{school}/applications/{application}/preview',
    [Tenant\School\ApplicationController::class, 'preview']
)->name('application.preview');

// Authorized Students and Agents Only
Route::middleware('auth:student')->group(function () {

    // Payment Result
    Route::post('{school}/payment/{student?}', [Tenant\School\PaymentController::class, 'track'])->name('payment.track');

    /*     Route::get('{school}/payment/{invoice}', [Tenant\School\PaymentController::class, 'response'])->name('payment.response');
     */
    //School Home
    Route::get('{school}', [Tenant\School\HomeController::class, 'index'])->name('school.home');

    // Log out User
    Route::post('{school}/logout', [Tenant\Auth\LoginController::class, 'logout'])->name('school.logout');

    // Application Resources
    Route::get('{school}/applications/', [Tenant\School\ApplicationController::class, 'index'])->name('application.index');

    //Profile
    Route::get('{school}/profile', [Tenant\School\StudentController::class, 'profile'])->name('student.profile');
    Route::post('{school}/profile/update', [Tenant\School\StudentController::class, 'update'])->name('student.profile.update');

    Route::get(
        '{school}/applications/{application}',
        [Tenant\School\ApplicationController::class, 'show']
    )->name('application.show')->missing(function($request){
        if($request->school->slug == 'kings-college-london'){
            if(count($request->all())){
                $params = "?".http_build_query($request->all());
            }else{
                $params ='';
            }

            return Redirect::to('kings-college-london/applications/apply-online' . $params);
        }
    })->middleware('appAuth');

    Route::get(
        '{school}/applications/{application}/thank-you',
        [Tenant\School\ApplicationController::class, 'thankYou']
    )->name('application.thank.you');

    Route::get(
        '{school}/applications/{application}/instructions',
        [Tenant\School\ApplicationController::class, 'showInstructions']
    )->name('application.instructions');

    Route::get('/{school}/invoice/pdf/{invoice}/view', [Tenant\School\ApplicationController::class, 'showInvoice'])->name('invoice.show');

    // Finance page in applicant dashboard
    Route::get('/{school}/finance', [Tenant\School\AccountingController::class, 'index'])->name('finance.show');
    Route::get('{school}/finance/getInvoicePayment', [Tenant\School\AccountingController::class, 'getInvoicePayment'])
        ->name('finance.getInvoicePayment');

    // Submitted Application page in applicant Dashboard
    Route::get('{school}/application-forms/', [Tenant\School\ApplicationController::class, 'showSubmittedApplications'])->name('show.submitted.applications');

    // New Applications page in applicant Dashboard
    Route::get('{school}/new-application-forms/', [Tenant\School\ApplicationController::class, 'newApplications'])->name('new.applications');

    // Submit Application
    Route::post(
        '{school}/applications/{application}',
        [Tenant\School\ApplicationController::class, 'submit']
    )->name('application.submit');

    Route::get(
        '{school}/applications/{application}/field-data/{field}',
        [Tenant\School\ApplicationController::class, 'getFieldData']
    )->name('application.field.data');

    // Get Submission Review
    Route::post(
        '{school}/applications/{application}/review',
        [Tenant\School\ApplicationController::class, 'review']
    )->name('submission.review');

    //Soft Delete Application Submission
    Route::delete('{school}/applications/{application}/{submission}', [Tenant\School\ApplicationController::class, 'deleteApplicationSubmission'])->name('application.submission.delete');

    // Parents
    Route::get('{school}/parents', [Tenant\School\ParentsController::class, 'index'])->name('school.parents');

    Route::get(
        '{school}/parent/child/create',
        [Tenant\School\ChildImpersonatController::class, 'create']
    )->name('school.parent.child.create');

    Route::post('{school}/parent/child/create', [Tenant\School\ChildImpersonatController::class, 'store']);

    Route::get(
        '{school}/parent/{application}/{student}/submit',
        [Tenant\School\ChildImpersonatController::class, 'submitChildApplication']
    )->name('school.parent.child.submit');

    // Payment
    Route::get(
        '{school}/{application}/payment/{invoice}',
        [Tenant\School\PaymentController::class, 'show']
    )->name('show.payment');

    Route::post(
        '{school}/{application}/payment/{invoice}',
        [Tenant\School\PaymentController::class, 'pay']
    )->name('payment.pay');

    // Stop Impersonating
    Route::delete(
        '{school}/parent/student/impersonate/stop',
        [Tenant\School\ChildImpersonatController::class, 'destroy']
    )->name('school.parent.child.destroy');

    // School Files Upload and Remove
    Route::post('{school}/upload/', [Tenant\School\UploadController::class, 'upload'])->name('school.file.upload');
    Route::post('{school}/destroy/', [Tenant\School\UploadController::class, 'destroy'])->name('school.file.destroy');
    Route::post('{school}/profileimage/upload/', [Tenant\StudentController::class, 'uploadImage'])->name('school.profileimage.upload');

    // Show Booking Detaila
    Route::get('{school}/booking/{booking}', [Tenant\School\BookingController::class, 'show'])->name('booking.show');

    Route::delete('{school}/booking/{booking}', [Tenant\School\BookingController::class, 'destroy'])->name('booking.destroy');

    Route::get('{school}/submissions/{submission}/review/show', [Tenant\School\SubmissionController::class, 'showReview'])
        ->name('school.submissions.show.review');

    Route::post('{school}/submissions/review/{submission}', [Tenant\School\SubmissionController::class, 'review'])
        ->name('school.submissions.review');


    // Messages
    Route::get('{school}/messages', [Tenant\School\MessageController::class, 'index'])->name('school.messages.index');

    Route::get('{school}/messages/{message}', [Tenant\School\MessageController::class, 'show'])->name('school.messages.show');

    Route::post('{school}/messages/attachments/upload', [Tenant\School\MessageController::class, 'uploadAttachment'])
    ->name('school.messages.attachments.upload');

    Route::post('{school}/messages/attachments/delete', [Tenant\School\MessageController::class, 'deleteAttachment'])
    ->name('school.messages.attachments.delete');

});

// School Files Upload and Remove
Route::post('{school}/upload/', [Tenant\School\UploadController::class, 'upload'])->name('school.file.upload');
Route::post('{school}/destroy/', [Tenant\School\UploadController::class, 'destroy'])->name('school.file.destroy');

// Quotation
/* Route::get('{school}/quotation/{quotation}', [Tenant\School\QuotationController::class, 'show'])->name('quotation.show'); */

/* Route::post(
'{school}/quotation/update',
[Tenant\School\QuotationController::class, 'update'])->name('quotation.update.price');
 */
/* Route::post(
'{school}/quotation/email',
[Tenant\School\QuotationController::class, 'sendQuotaionEmail'])->name('send.quotation.email');
 */

/* Route::post(
'{school}/quotation/{quotation}/book',
[Tenant\School\QuotationController::class, 'quotationBooking'])->name('quotation.booking'); */

/* Route::post(
'{school}/quotation/{quotation}/login',
[Tenant\School\QuotationController::class, 'quotationLogin'])->name('quotation.login'); */

/* Route::get(
'{school}/quotation/{quotation}/login',
[Tenant\School\QuotationController::class, 'quotationLogin'])->name('quotation.login'); */

Route::get('assistant/{assistant}', [Tenant\BuilderAssistantController::class, 'show'])
    ->name('assistant.show');

Route::get('quote/{booking}', [Tenant\BuilderAssistantController::class, 'quoteShow'])
    ->name('quote.show');

Route::post(
    'invoices/{invoice}/paid',
    [Tenant\InvoiceController::class, 'changeInvoiceAsPaid']
)->name('invoices.changeInvoiceAsPaid');

Route::get(
    'invoices/{invoice}/paid',
    [Tenant\InvoiceController::class, 'createPaid']
)->name('invoices.createPaid');

Route::get(
    'invoices/{student}/create',
    [Tenant\InvoiceController::class, 'create']
)->name('invoices.create');

Route::post(
    'invoices/store',
    [Tenant\InvoiceController::class, 'store']
)->name('invoices.store');

Route::delete(
    'invoices/{invoice}',
    [Tenant\InvoiceController::class, 'destroy']
)->name('invoices.destroy');

Route::get(
    'invoices/create',
    [Tenant\InvoiceController::class, 'createPolymorph']
)->name('invoices.createPolymorph');

Route::get(
    'invoices/update/{invoice}',
    [Tenant\InvoiceController::class, 'editPolymorph']
)->name('invoices.editPolymorph');

Route::get(
    'invoices/create/product',
    [Tenant\InvoiceController::class, 'createProduct']
)->name('invoices.createProduct');

// Assitant
Route::get(
    '{school}/assistants/{assistantBuilder}/{step?}',
    [Tenant\VirtualRecruitmentAssistantController::class, 'show']
)->name('assistants.show');

Route::post(
    '{school}/assistants/{assistantBuilder}/login',
    [Tenant\VirtualRecruitmentAssistantController::class, 'login']
)->name('assistants.login');

Route::get(
    '{school}/assistants/recuperate/email/{assistant}/{user}',
    [Tenant\VirtualRecruitmentAssistantController::class, 'recuperateMailAssistant']
)->name('assistants.recuperate.email');

Route::post(
    '{school}/assistants/{assistantBuilder}/register',
    [Tenant\VirtualRecruitmentAssistantController::class, 'register']
)->name('assistants.register');

Route::post(
    '{school}/assistants/{assistantBuilder}/email',
    [Tenant\VirtualRecruitmentAssistantController::class, 'sendSummaryEmail']
)->name('assistants.email');

Route::get(
    '{school}/assistants/{assistantBuilder}/{assistant}/thank-you',
    [Tenant\VirtualRecruitmentAssistantController::class, 'assistantThankYouPage']
)->name('recruitment_assistant.thank.you');

// Call Me Back
Route::get(
    '{school}/assistants/{assistantBuilder}/{step?}/call-back',
    [Tenant\School\CallBackController::class, 'show']
)->name('call.back.show');

Route::post(
    '{school}/assistants/{assistantBuilder}/{step?}/request',
    [Tenant\School\CallBackController::class, 'request']
)->name('call.back.request');

Route::post(
    '{school}/assistants/{assistantBuilder}/{step?}/status',
    [Tenant\School\CallBackController::class, 'status']
)->name('call.back.status');

Route::post(
    '{school}/assistants/{assistantBuilder}/{step?}/add',
    [Tenant\School\CallBackController::class, 'add']
)->name('call.back.add');

//Quotation -- Upgraded
Route::get(
    '{school}/quotations/{quotation}/{booking}/thank-you',
    [Tenant\School\QuotationsController::class, 'quotationThankYouPage']
)->name('quotations.thank.you');

Route::post(
    '{school}/quotations/{quotation}/email',
    [Tenant\School\QuotationsController::class, 'sendQuotaionEmail']
)->name('send.quotations.email');

Route::get(
    '{school}/quotations/recuperate/email/{booking}/{user}',
    [Tenant\School\QuotationsController::class, 'recuperateMailQuotation']
)->name('quotations.recuperate.email');

Route::post(
    '{school}/quotations/{quotation}/book',
    [Tenant\School\QuotationsController::class, 'quotationBooking']
)->name('quotations.booking');

Route::post(
    '{school}/quotations/{quotation}/email/book',
    [Tenant\School\QuotationsController::class, 'quotationBookingByMail']
)->name('quotations.booking.email');

Route::post(
    '{school}/quotations/{quotation}/login',
    [Tenant\School\QuotationsController::class, 'quotationLogin']
)->name('quotations.login');

Route::get(
    '{school}/quotations/{quotation}/{step?}',
    [Tenant\School\QuotationsController::class, 'show']
)->name('quotations.show');

// No Login Application
Route::get(
    '{school}/application/{application}',
    [Tenant\School\NoAuthApplicationController::class, 'showNoLogin']
)->name('application.show.no-login');

Route::get(
    '{school}/application/{application}/payment/{invoice}',
    [Tenant\School\PaymentController::class, 'show']
)->name('application.payment.show.no-login');

Route::post(
    '{school}/application/{application}/payment/{invoice}',
    [Tenant\School\PaymentController::class, 'pay']
)->name('application.payment.pay.no-login');

Route::get('/payments/create/Polymorph', [PaymentController::class, 'create'])->name('payment.createPolymorph');

Route::get('/payments/edit/{invoice_payment}/Polymorph', [PaymentController::class, 'edit'])->name('payment.editPolymorph');

Route::post('/payments/storePolymorph', [PaymentController::class, 'storePoymorph'])->name('payment.storePolymorph');

Route::delete('/payments/delete/{invoice_payment}/Polymorph', [PaymentController::class, 'destroyPoymorph'])->name('payment.destroyPolymorph');

Route::get('/{school}/invoice/{invoice}', [Tenant\PaymentController::class, 'pay'])->name('invoice.pay');

Route::get('promocodes/apply/promo/code', [Tenant\PromocodeController::class, 'apply'])->name('promocodes.apply');

Route::get('{school}/campuses/{campus}/information', [Tenant\CampusController::class, 'information'])->name('campus.information');
Route::get('campuses/{campus}/calendar', [Tenant\CampusController::class, 'calendar'])->name('campuses.calendar');

Route::get('{school}/programs/{program}/information', [Tenant\ProgramController::class, 'information'])->name('program.information');

Route::get('{school}/courses/calendar/show', [Tenant\CoursesCalendarController::class, 'show'])->name('coursesCalendar.show');

Route::get('calendar/index', [Tenant\CalendarController::class, 'index'])->name('calendar.index');

Route::post('{school}/{application}/signature/eversign', [Tenant\EversignController::class, 'eversign'])->name('sign.eversign');

Route::get(
    '{school}/{application}/{submission}/{action}/after/signature/eversign',
    [Tenant\EversignController::class, 'afterEversign']
)
    ->name('sign.after.eversign');

Route::get('{school}/{application}/{submission}/redirect/afterSubmit', [Tenant\School\ApplicationController::class, 'redirectAfterSubmit'])
    ->name('redirect.afterSubmit');

Route::get('{school}/{application}/signed/{submission}', [Tenant\EversignController::class, 'signed'])
    ->name('eversign.signed');



Route::get('{school}/submission/change/requestUnlock', [Tenant\SubmissionController::class, 'requestUnlock'])->name('submissions.requestUnlock');

Route::get('submission/change/lock', [Tenant\SubmissionController::class, 'unlock'])->name('submissions.unlock');

Route::get('{school}/courses/campuses', [Tenant\CourseController::class, 'courseFromCampus'])->name('courses.campus');

Route::get('{school}/courses/startDates/available', [Tenant\CourseController::class, 'startDatesAvailable'])
    ->name('courses.startDates.available');

Route::get('{school}/courses/startDates', [Tenant\CourseController::class, 'startDatesAndAddons'])
    ->name('courses.startDatesAndAddons');

Route::get('{school}/date/addons', [Tenant\DateAddonController::class, 'getDateAddons'])->name('date.addons');

Route::get('{school}/courses/new/form', [Tenant\CourseController::class, 'newForm'])->name('course.new.form');

Route::get('{school}/programs/search', [Tenant\ProgramController::class, 'filterProgram'])->name('programs.filter');

Route::get('{school}/programs/startDatesAndAddons', [Tenant\ProgramController::class, 'startDatesAndAddons'])
    ->name('programs.startDatesAndAddons');

Route::post('{school}/cart/courseDate', [Tenant\CartController::class, 'courseDate'])->name('cart.courseDate');

Route::put('{school}/cart/deleteCourse', [Tenant\CartController::class, 'deleteCourse'])->name('cart.deleteCourse');

Route::put('{school}/cart/addonsCourse', [Tenant\CartController::class, 'addonsCourse'])->name('cart.addonsCourse');

Route::put('{school}/cart/addonsDateCourse', [Tenant\CartController::class, 'addonsDateCourse'])->name('cart.addonsDateCourse');

Route::post('{school}/cart/programDate', [Tenant\CartController::class, 'programDate'])->name('cart.programDate');

Route::put('{school}/cart/addonsProgram', [Tenant\CartController::class, 'addonsProgram'])->name('cart.addonsProgram');

Route::post('{school}/cart/addons', [Tenant\CartController::class, 'addons'])->name('cart.addons');

Route::post('{school}/cart/show', [Tenant\CartController::class, 'show'])->name('cart.show');

Route::post('{school}/{application}/paymentType', [Tenant\PaymentController::class, 'paymentType'])->name('payment.type');

Route::post(
    '{school}/{application}/{field}/sync/',
    [Tenant\School\ApplicationController::class, 'getSyncData']
)->name('application.field.sync');
