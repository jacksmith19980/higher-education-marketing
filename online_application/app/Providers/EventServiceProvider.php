<?php

namespace App\Providers;

use App\Events\Tenant\Instructor\InstructorCreated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        // When School is Created
        \App\Events\Tenant\TenantWasCreated::class => [
            \App\Listeners\Tenant\CreateTenantDatabase::class,
        ],

        // When School is Deleted
        \App\Events\Tenant\TenantDeleted::class => [
            \App\Listeners\Tenant\DeleteTenantDatabase::class,
        ],

        // School is Identified
        \App\Events\Tenant\TenantIdentified::class => [
            \App\Listeners\Tenant\RegisterTenant::class,
        ],

        // School DB is Created
        \App\Events\Tenant\TenantDatabaseCreated::class => [
            \App\Listeners\Tenant\SetupTenantDatabase::class,
        ],

        // School Users were invited
        \App\Events\Tenant\UsersWereInvited::class => [
            \App\Listeners\Tenant\InviteSchoolUsers::class,
        ],

        // New Agency Is Created
        \App\Events\Tenant\Agency\AgencyIsCreated::class => [
            \App\Listeners\Tenant\Agency\AgencyCreatedWebhook::class,
        ],

        // New Agency Is Updated
        \App\Events\Tenant\Agency\AgencyIsUpdated::class => [
            \App\Listeners\Tenant\Agency\AgencyUpdatedWebhook::class,
        ],

        // New Agency Is Approved
        \App\Events\Tenant\Agency\AgencyIsApproved::class => [
            \App\Listeners\Tenant\Agent\ActivateAgents::class,
        ],



        // Agency Application Submitted
        \App\Events\Tenant\Agency\AgencyApplicationSubmitted::class => [
            \App\Listeners\Tenant\Agency\AgencyApplicationWebhook::class,
        ],
        // Agency Application Updated
        \App\Events\Tenant\Agency\AgencyApplicationUpdated::class => [
            \App\Listeners\Tenant\Agency\AgencyApplicationUpdatedWebhook::class,
        ],

        // Agents Added to Agency
        \App\Events\Tenant\Agent\AgentsAddedToAgency::class => [
            \App\Listeners\Tenant\Agent\CreateAgents::class,
            \App\Listeners\Tenant\Agent\AgentsCreatedWebhook::class,
        ],

        // Agent Registered
        \App\Events\Tenant\Agent\AgentRegistered::class => [
            \App\Listeners\Tenant\Agent\AgentCreatedWebhook::class,
            \App\Listeners\Tenant\Agent\SendAgentActivationEmail::class,
        ],

        \App\Events\Tenant\Agent\ActivationEmailRequested::class => [
            \App\Listeners\Tenant\Agent\ReSendAgentActivationEmail::class,
        ],

        \App\Events\Tenant\Agent\InvitationEmailRequested::class => [
            \App\Listeners\Tenant\Agent\ReSendAgentInvitationEmail::class,
        ],

        // Application Created
        \App\Events\Tenant\Application\ApplicationCreated::class => [
            \App\Listeners\Tenant\Application\AddDefaultSections::class,
        ],

        // Student Registered
        \App\Events\Tenant\Student\StudentRegistred::class => [
            \App\Listeners\Tenant\Student\StudentRegistredHook::class,
            \App\Listeners\Tenant\Student\UpdateStudentBooking::class,
            \App\Listeners\Tenant\Student\SendWelcomeEmail::class,
        ],

        // Parent Registered
        \App\Events\Tenant\Parent\ParentRegistred::class => [
            \App\Listeners\Tenant\Parent\ParentRegistredHook::class,
            \App\Listeners\Tenant\Parent\UpdateParentInvoice::class,
            //'App\Listeners\Tenant\Parent\SendPasswordEmail',
        ],

        // Student Created (By Agent or Admission)
        \App\Events\Tenant\Student\StudentCreated::class => [
            \App\Listeners\Tenant\Student\StudentCreatedHook::class,
            \App\Listeners\Tenant\Student\StudentCreatedNotification::class,
        ],

        // Child Account Created (By Parent)
        \App\Events\Tenant\Student\ChildAccountCreated::class => [
            \App\Listeners\Tenant\Student\ChildCreatedHook::class,
            \App\Listeners\Tenant\Student\CreateBookingInvoice::class,
            \App\Listeners\Tenant\Student\ChildCreatedNotification::class,

        ],
        // Application Submitted
        \App\Events\Tenant\Application\ApplicationSubmitted::class => [
            \App\Listeners\Tenant\Application\RunApplicationSubmittedIntegrations::class,
            \App\Listeners\Tenant\Application\CreateBookingInvoice::class,
            \App\Listeners\Tenant\Application\AddSubmittionNotification::class,
            \App\Listeners\Tenant\Application\InitiatSubmissionStatus::class,
        ],

        // Application Updated
        \App\Events\Tenant\Application\ApplicationSubmissionUpdated::class => [
            \App\Listeners\Tenant\Application\AddSubmittionUpdatedNotification::class,
            \App\Listeners\Tenant\Application\RunApplicationUpdatedIntegrations::class,
            \App\Listeners\Tenant\Application\UpdateSubmissionStatus::class,
        ],
        // Application Reached last step
        \App\Events\Tenant\Application\ApplicationLastStep::class => [
            \App\Listeners\Tenant\Application\RunApplicationActions::class,
            \App\Listeners\Tenant\Application\CreateBookingInvoice::class,
            \App\Listeners\Tenant\Application\UpdateSubmissionStatus::class,
        ],
        // Application Submission Approved
        \App\Events\Tenant\Application\ApplicationSubmissionApproved::class => [
            \App\Listeners\Tenant\Application\RunApplicationApprovedIntegrations::class,
        ],
        \App\Events\Tenant\Submission\SubmissionStatusChanged::class => [
            \App\Listeners\Tenant\Submission\RunSubmissionStatusIntegrations::class,
        ],

        // Quotaion Email Requested
        \App\Events\Tenant\Quotation\QuotationEmailRequested::class => [
            \App\Listeners\Tenant\Quotation\RunQuotationRequestedIntegration::class,
            \App\Listeners\Tenant\Quotation\SendQuotationEmail::class,
        ],

        // Quotaion Email Requested
        \App\Events\Tenant\Payment\InvoicePaid::class => [
            \App\Listeners\Tenant\Payment\SendPaymentNotificationEmail::class,
            \App\Listeners\Tenant\Payment\UpdateInvoiceStatus::class,
            \App\Listeners\Tenant\Payment\RunInvoicePaidIntegration::class,
        ],

        // Assistant Email Requested
        \App\Events\Tenant\Assistant\AssistantEmailRequested::class => [
            \App\Listeners\Tenant\Assistant\RunAssistantRequestedIntegration::class,
            \App\Listeners\Tenant\Assistant\SendAssistantEmail::class,
        ],
        // Follow Up  Requested
        \App\Events\Tenant\Assistant\FollowUpRequested::class => [
            \App\Listeners\Tenant\Assistant\SaveFollowUpRequest::class,
        ],

        // Follow Up  Scheduled
        \App\Events\Tenant\Assistant\FollowUpScheduled::class => [
            \App\Listeners\Tenant\Assistant\SendNotificationEmail::class,
        ],
        // Follow Up  Scheduled
        \App\Events\Tenant\Assistant\FollowUpUpdate::class => [
            \App\Listeners\Tenant\Assistant\UpdateFollowUpStatus::class,
        ],

        // Classroom Create
        \App\Events\Tenant\Classroom\ClassroomCreated::class => [
            \App\Listeners\Tenant\ClassroomSlot\CreateClassroomSlots::class,
        ],

        // Classroom update
        \App\Events\Tenant\Classroom\ClassroomUpdated::class => [
            \App\Listeners\Tenant\ClassroomSlot\UpdateClassroomSlots::class,
        ],

        \App\Events\Tenant\School\UnlockRequestEvent::class => [
            \App\Listeners\Tenant\School\UnlockRequestSendNotificationListener::class,
            \App\Listeners\Tenant\Application\UnlockRequestSubmissionStatusListener::class,
        ],

        \App\Events\Tenant\School\UnlockEvent::class => [
            \App\Listeners\Tenant\School\UnlockSendNotificationListener::class,
            \App\Listeners\Tenant\Application\UnlockSubmissionStatusListener::class,
        ],

        /* 'App\Events\Tenant\School\SubmissionContractCreated' => [
            //'App\Listeners\Tenant\Submission\UpdateSubmissionStatus',
            'App\Listeners\Tenant\Submission\CreateContract',
        ],

        'App\Events\Tenant\School\SubmissionContractSent' => [
            'App\Listeners\Tenant\Submission\UpdateSubmissionStatus'
        ], */

        \App\Events\Tenant\Instructor\InstructorCreated::class => [
            \App\Listeners\Tenant\Instructor\InstructorCreatedEmailNotificationListener::class,
        ],


        \App\Events\Tenant\Message\MessageSent::class => [
            \App\Listeners\Tenant\Message\CreateNotification::class,
            \App\Listeners\Tenant\Message\SaveAttachments::class,
            \App\Listeners\Tenant\Message\SendNotificationEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
