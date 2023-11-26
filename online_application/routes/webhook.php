<?php

use App\Http\Controllers\Tenant;
use App\Http\Controllers\Tenant\School;
use Illuminate\Support\Facades\Route;


Route::post(
    '{school}/application/{application}',
    [Tenant\School\NoAuthApplicationController::class, 'submit']
)->name('application.submit.no.login');

Route::get('submission/success', [Tenant\School\NoAuthApplicationController::class, 'submissionSuccess'])->name('submitted.successful');

// Payment Result
Route::post('webhook/{school}/payment', [Tenant\School\PaymentResponseController::class, 'response'])->name('payment.response');

Route::get('webhook/{school}/payment', [Tenant\School\PaymentResponseController::class, 'response'])->name('payment.response');

Route::get('webhook/{school}/call/response', [Tenant\School\CallBackController::class, 'response'])->name('call.back.response');

Route::post('webhook/{school}/student/stage', [Tenant\School\StudentStageWebhookController::class, 'response']);

Route::get('{school}/{application}/signature/eversign/signed', [Tenant\School\EversignController::class, 'signed'])->name('sign.eversign.signed');

/* Route::get('webhook/{school}/docusign/contracts', [Tenant\SubmissionController::class, 'webhookVerification']); */


Route::post('webhook/{school}/docusign/contracts', [Tenant\SubmissionController::class, 'contractEvent'])->name('docusign.webhooks');


Route::post('webhook/{school}/esignature/contracts', [Tenant\SubmissionController::class, 'contractEvent'])->name('docusign.webhooks');


Route::get('webhook/{school}/docusign/contracts', [Tenant\ContractController::class, 'webhookVerification']);
