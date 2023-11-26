<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Application\ApplicationHelpers;
use App\Helpers\Application\ProgramHelpers;
use App\Helpers\Invoice\InvoiceHelpers;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\ModelHelpers;
use App\Http\Controllers\Controller;
use App\Services\Tenant\ServicesService;
use App\Tenant\Models\Addon;
use App\Tenant\Models\Application;
use App\Tenant\Models\Course;
use App\Tenant\Models\EducationalService;
use App\Tenant\Models\EducationalServiceCategory;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\Invoiceable;
use App\Tenant\Models\InvoiceStatus;
use App\Tenant\Models\Program;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;
use Response;

class InvoiceController extends Controller
{
    /**
     * @var ServicesService $service
     */
    private $service;

    public function __construct(ServicesService $service)
    {
        $this->service = $service;

    }

    public function pdf(Invoice $invoice, $action, Request $request)
    {
        $invoice->load('booking', 'student', 'status', 'application');
        $settings = Setting::byGroup();
        $currency = isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '$';

        if ($action == 'view') {
            $html = view('back.students.invoices.invoice', compact('invoice', 'settings', 'currency'))->render();

            return PDF::loadHTML($html)->stream();
        }

        if ($action == 'download') {
            $name = Str::slug($invoice->student->name).'-'.$invoice->uid.'.pdf';

            return PDF::loadView('back.students.invoices.invoice', ['invoice' => $invoice, 'settings' => $settings, 'currency' => $currency])
                ->download($name);
        }
    }

    public function changeInvoiceAsPaid(Request $request, Invoice $invoice)
    {
        $invoiceStatus = new InvoiceStatus();
        $invoiceStatus->status = 'Paid';

        $created = strtotime($request->Created);

        $request['Created'] = $created;

        $invoiceStatus->properties = $request->all();

        $invoiceStatus->invoice_id = $invoice->id;

        $invoiceStatus->save();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['Id' => $invoice->id],
            ]
        );
    }

    public function destroy($invoice_id)
    {
        $invoice = Invoice::where('uid', $invoice_id)->firstOrFail();

        // changing invoice payment to null to avoid payment will deleted in cascade mode.
        if (count($invoice->payments) > 0) {
            $message = 'This invoice have some payments, if you want to delete those payments you must delete them separately';
        } else {
            $message = 'This invoice was deleted correctly';
        }

        foreach ($invoice->payments as $payment) {
            $payment->invoice_id = null;
            $payment->save();
        }

        if ($response = $invoice->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'removedId' => $invoice->id,
                        'message' => $invoice->id,
                    ],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 404,
                    'response' => $response,
                ]
            );
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'        => 'required',
            'total'             => 'required',
            'amount_category'   => 'required',
            'payment_gateway'   => 'required',
            'program_id'        => 'required',
        ]);

        $invoice = InvoiceHelpers::addProgramInvoice(
            $request->program_id,
            $request->student_id,
            $request->total,
            $request->payment_gateway
        );

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['Id' => $invoice->id],
            ]
        );
    }

    public function create(Request $request, Student $student)
    {
        $route = 'invoices.store';

        return view(
            'back.students._partials.invoice-create-form',
            compact('student', 'route')
        );
    }

    public function createPaid(Invoice $invoice)
    {
        $route = 'invoices.changeInvoiceAsPaid';

        return view(
            'back.students._partials.invoice-paid-form',
            compact('invoice', 'route')
        );
    }

    /**
     * TODO Change name to create
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function createPolymorph(Request $request)
    {
        $students = ModelHelpers::convertFirstNameLastnameInNameAssocWithId(
            Student::all()
        );

        $student = null;
        if (isset($request->student_id)) {
            $student = Student::findOrfail($request->student_id);
        }

        return view('back.students.invoices.invoice-create', compact('students', 'student'));
    }

    public function editPolymorph(Invoice $invoice, Request $request)
    {

        $students = ModelHelpers::convertFirstNameLastnameInNameAssocWithId(Student::all());
        $student = Student::findOrfail($invoice->student_id);
        $products = Invoiceable::get()->pluck('title', 'id');

        return view(
            'back.students.invoices.invoice-create',
            compact('students', 'invoice', 'student', 'products')
        );
    }

    public function createProduct($payload)
    {
        $order = $payload['order'];
        $educationalServiceCategories = EducationalServiceCategory::where('is_published', true)->pluck('name', 'name', 'color');
        $uid = rand();
        $products = [];

        $products['Application'] = ApplicationHelpers::getApplication();
        $products['Course'] = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $products['Program'] = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $products['Addon'] = ApplicationHelpers::getAddons();
        $products['Services'] = $this->service->getServices();

        $html = view('back.students.invoices.product-new-line', compact('order', 'educationalServiceCategories', 'uid', 'products'))->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function productsByCategory($payload)
    {
        $order = $payload['order'];
        $category = $payload['category'];
        $html = '';

        $products = InvoiceHelpers::getProductsBycategory($category);

        if ($products != null) {
            $html = view('back.shared._partials.field_value', [
                'data'      => $products,
                'name'      => $payload['field'] == 1 ? 'properties[product]' : 'educational_product',
                'attr'      => 'onchange=app.amountByProduct(this) data-order='.$order.' data-category='.$category,
                'label'     => $payload['field'] == 1 ? 'Educational Product' : '',
                'required'  => true,
                'class'     => 'ajax-form-field',
            ])->render();
        }

        return response()->json([
            'status' => 200,
            'response' => 'success',
            'extra' => [
                'product' => $html,
            ],
        ]);
    }

    public function amountByProduct($payload)
    {
        $data = explode(',', $payload['id']);
        $id = $data[0];
        $category = $data[1];
        $amount = 0;
        $description = 'Product Not Found';
        switch ($category) {
            case 'Services':
                $product = EducationalService::findOrFail($id);
                $amount = $product->amount;
                $description = $product->description;
                break;
            case 'Application':
                $product = Application::findOrFail($id);
                $amount = isset($product->properties['application_fees']) ? $product->properties['application_fees'] : 0;
                $description = $product->title;
                break;
            case 'Course':
                $product = Course::findOrFail($id);
                $amount = 0;
                $description = $product->title;
                if ($product->dates) {
                    if ($product->dates[0]->date_type == 'specific-dates') {
                        $amount = $product->dates[0]->properties['date_price'];
                    }
                }
                break;
            case 'Program':
                $product = Program::findOrFail($id);
                $fee_registeration = isset($product->properties['program_registeration_fee']) ? $product->properties['program_registeration_fee'] : 0;
                $fee_materials = isset($product->properties['program_materials_fee']) ? $product->properties['program_materials_fee'] : 0;

                if (isset($product->properties['date_price']) && count($product->properties['date_price']) > 0) {
                    $price = max($product->properties['date_price']);
                } else {
                    $price = 0;
                }
                $amount = floatval($fee_registeration) + floatval($fee_materials) + floatval($price);
                $description = $product->title;
                break;
            case 'Addon':
                $product = Addon::findOrFail($id);
                $amount = $product->price;
                $description = $product->title;
                break;
        }

        return response()->json([
            'status' => 200,
            'response' => 'success',
            'extra' => [
                'amount' => $amount,
                'description' => $category.' - '. $description,
            ],
        ]);
    }

    /**
     * TODO Change name to store
     * @param Request $request
     */
    public function storePolymorph($payload)
    {
        $invoice_request = $payload['invoice'];
        $total = 0;

        $is_update = isset($invoice_request['invoice_id']) &&
            $invoice_request['invoice_id'] != '' &&
            $invoice_request['invoice_id'] != null;

        if ($is_update) {
            $invoice = Invoice::where('uid', $invoice_request['invoice_id'])->first();
        } else {
            $invoice = new Invoice();
            $invoice->uid = rand(100000, 1000000);
        }

        $invoice->payment_gateway = '-';
        $invoice->student_id = $invoice_request['student_id'];
        $invoice->due_date = $invoice_request['due_date'];
        $invoice->created_at = $invoice_request['invoice_date'];
        $invoice->enabled = true;
        $invoice->total = $total;
        $invoice->properties = [
            'invoice_message' => $invoice_request['invoice_message'],
            'billing_address' => $invoice_request['billing_address'],
        ];
        $invoice->save();

        $product = null;
        $invoice->invoiceables()->delete();

        if (isset($invoice_request['products']) && count($invoice_request['products']) > 0) {
            $i = 1;
            foreach ($invoice_request['products'] as $product) {
                $data = explode(',', $product['educational_product']);
                $product_id = $data[0];
                $category = $data[1];
                $total = $total + $product['amount'];
                $invoiceable_payload = [
                    'uid' => rand(100000, 1000000),
                    'student_id' => $invoice_request['student_id'],
                    'quantity' => 1,
                    'amount' => $product['amount'],
                    'properties' => json_encode([
                        'description' => $product['description'],
                        'taxable' => (bool)$product['taxable'],
                    ]),
                ];

                $title = '';
                switch ($category) {
                    case 'Services':
                        $product = EducationalService::findOrFail($product_id);
                        $title = $product->description;
                        break;
                    case 'Application':
                        $product = Application::findOrFail($product_id);
                        $title = $product->title;
                        break;
                    case 'Course':
                        $product = Course::findOrFail($product_id);
                        $title = $product->title;
                        break;
                    case 'Program':
                        $product = Program::findOrFail($product_id);
                        $title = $product->title;
                        break;
                    case 'Addon':
                        $product = Addon::findOrFail($product_id);
                        $title = $product->title;
                        break;
                }

                $invoiceable_payload['title'] = $title;
                $product->invoiceable()->save($invoice, $invoiceable_payload);
                $i += 1;
            }
        }

        $invoice->total = $total;
        $invoice->save();

        return response()->json([
            'status' => 200,
            'response' => 'success',
            'extra' => [
                'invoice_id' => $invoice->id,
                'invoice_uid' => $invoice->uid,
            ],
        ]);
    }
}
