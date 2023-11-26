<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Tenant\Models\Quotation;
use App\Tenant\Traits\Integratable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class QuotationsController extends Controller
{
    use Integratable;

    public function __construct()
    {
        $this->middleware('plan.features:quote_builder');
    }

    public function index()
    {
        $params = [
            'modelName' => Quotation::getModelName(),
        ];
        $quotations = Quotation::with('application')->get();
        $school = School::byUuid(session('tenant'))->firstOrFail();

        return view('back.quotation.index', compact('quotations', 'params', 'school'));
    }

    public function create()
    {
        $applications = Application::get()->pluck('title', 'id')->toArray();
        $campuses = Campus::get()->pluck('title', 'id')->toArray();
        $courses = Course::get()->pluck('title', 'id')->toArray();
        $programs = Program::all();
        $requestType = $this->getRequestType();

        return view('back.quotation.create', compact('applications', 'campuses', 'programs', 'courses', 'requestType'));
    }

    public function store(Request $request)
    {
        $properties = $this->cleanQuotationPorperties($request);
        $quotation = new Quotation();
        $quotation->title = $request->title;
        $quotation->slug = Str::slug($request->title);
        $quotation->description = $request->description;
        $quotation->properties = $properties;
        $quotation->application()->associate($request->application);
        $quotation->save();

        return redirect(route('quotations.index'));
    }

    public function show(Quotation $quotation)
    {
        //
    }

    public function edit(Quotation $quotation)
    {
        $applications = Application::get()->pluck('title', 'id')->toArray();
        $campuses = Campus::get()->pluck('title', 'id')->toArray();
        $courses = Course::get()->pluck('title', 'id')->toArray();
        $programs = Program::all();

        //dd($quotation->properties);
        return view('back.quotation.edit', compact(
            'quotation',
            'applications',
            'campuses',
            'courses',
            'programs'
        ));
    }

    /**
     * Get List of Request Type from Mautic
     *
     * @return array
     */
    protected function getRequestType()
    {
        $data = [];
        if ($mautic = $this->inetgration()) {
            $data = $mautic->getFieldList('request_type');
        }

        return $data;
    }

    public function update(Request $request, Quotation $quotation)
    {
        $properties = $this->cleanQuotationPorperties($request);

        $sql = $quotation->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'properties'    => $properties,
        ]);

        $quotation->application()->associate($request->application);
        $quotation->save();

        return redirect(route('quotations.index', $quotation));
    }

    public function destroy(Quotation $quotation)
    {
        //
    }

    /**
     * Remove unwanted properties form the request
     */
    protected function cleanQuotationPorperties(Request $request)
    {
        $properties = $request->properties;
        if (! isset($request->properties['enable_accommodation'])) {
            unset($properties['enable_accommodation_multiselect']);
            unset($properties['accommodation_cost_template']);
            unset($properties['transfer_step']);
            unset($properties['accomodation_options']);
            unset($properties['accomodation_options_price']);
        }

        if (! isset($request->properties['enable_transfer'])) {
            unset($properties['enable_transfer_multiselect']);
            unset($properties['transfer_options']);
            unset($properties['transfer_options_price']);
        }

        if (! isset($request->properties['enable_misc'])) {
            unset($properties['enable_mics_multiselect']);
            unset($properties['mics_cost_template']);
            unset($properties['misc_options']);
            unset($properties['misc_options_price']);
        }

        if ($request->files) {
            foreach ($request->files as $name => $file) {
                $properties[$name]['path'] = Storage::putFile('/'.session('tenant'), $request->file($name));
                $properties[$name]['name'] = $request->file($name)->getClientOriginalName();
            }
        }

        return $properties;
    }

    public function listAssistantsRequestedByMailWithoutApplication(Request $request)
    {
        $assistants = Booking::where('user_id', 'regexp', '^[a-zA-Z]+$')->get();

        return view(
            'back.recruitment_assistant.mailRequestedStudents.index',
            compact('assistants')
        );
    }
}
