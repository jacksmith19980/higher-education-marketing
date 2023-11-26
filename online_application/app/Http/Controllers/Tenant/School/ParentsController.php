<?php

namespace App\Http\Controllers\Tenant\School;

use App\Helpers\School\AgentHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Quotation;
use App\Tenant\Models\Student;
use Auth;
use Illuminate\Http\Request;
use Session;

class ParentsController extends Controller
{
    public function index(School $school)
    {
        $user = Auth::guard('student')->user();

        // Get Available Applications
        $applications = AgentHelpers::getApplications();

        $schoolApplications = Application::with('quotation')->get();

        // Bookings
        $bookings = $user
            ->noAgentBookings()
            ->orderBy('created_at', 'DESC')
            ->orderBy('quotation_id', 'DESC')
            ->with(['quotation', 'quotation.application', 'invoices', 'invoices.status'])->get();

        // School Quotations
        $schoolQuotations = Quotation::get();

        $submissions = $user->submissions;

        // Get Children
        $children = Student::where([
                'parent_id' => $user->id,
                'role' => 'student', ])->orderBy('created_at', 'desc')->with(
                    ['invoices',
                               /*  'invoices' => function ($query) {
                                        $query->where('enabled', true);
                                }, */
                                'invoices.status',
                                'submissions',
                                'submissions.booking',
                                'submissions.booking.invoices',
                                'submissions.booking.invoices.status',
                        ]
                )->get();
        Session::forget('child-impersonate');

        return view(
            'front.parent.index',
            compact(
                'school',
                'applications',
                'children',
                'bookings',
                'submissions',
                'schoolQuotations',
                'schoolApplications'
            )
        );
    }
}
