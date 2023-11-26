<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoicePayments;
use App\Tenant\Models\Submission;
use Illuminate\Http\Request;
use Response;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $latestSubmissions = Submission::with(['student', 'statuses'])->orderByDesc('id')->take(3)->get();
        $latestPayments = InvoicePayments::query()->latest()->limit(3)->get();
        $latestInvoices = Invoice::query()->latest()->limit(3)->get();
        $latestTransactions = $latestPayments->merge($latestInvoices)
            ->sortByDesc('created_at')
            ->take(5)
            ->map(function ($transaction) {
                return (object) [
                    'type' => $transaction instanceof Invoice ? 'Invoice' : 'Payment',
                    'amount' => $transaction instanceof Invoice ? $transaction->total : $transaction->amount_paid,
                    'payment_method' => $transaction->payment_method,
                    'applicant' => $transaction instanceof Invoice ? $transaction->student : $transaction->invoice->student,
                ];
            });

        return view('back.schools.dashboard', compact('latestSubmissions', 'latestTransactions'));
    }

    public function getWidget(Request $request)
    {
        if (isset($request->widget)) {
            $name = str_replace(' ', '', ucwords(str_replace('-', ' ', $request->widget)));
            $widget = "App\\Widgets\\$name";

            return (new $widget($request))->build();
        }
    }
}
