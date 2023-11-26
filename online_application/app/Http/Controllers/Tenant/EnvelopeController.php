<?php

namespace App\Http\Controllers\Tenant;

use Sign;
use Response;
use App\School;
use Carbon\Carbon;
use App\Tenant\Models\Field;
use Illuminate\Http\Request;
use App\Tenant\Models\Student;
use App\Tenant\Models\Envelope;
use App\Tenant\Models\Submission;
use App\Tenant\Traits\HasCampuses;
use App\Http\Controllers\Controller;
use App\Helpers\Permission\PermissionHelpers;

class EnvelopeController extends Controller
{
    use HasCampuses;
    const PERMISSION_BASE = "envelope";

    public function __construct()
    {
        $this->middleware('plan.features:e-signature');
    }

    public function index()
    {

        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }
        $params = [
            'modelName' => Envelope::getModelName(),
        ];

        $envelopes = Envelope::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->with('campuses')->get();
        $school = School::byUuid(session('tenant'))->firstOrFail();
        return view('back.envelopes.index', compact('envelopes', 'params', 'school' , 'permissions'));
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        if(!$serviceName = Sign::getServiceName()){
            return redirect(route('plugins.index'));
        }

        // if user is not permitted to create envelopes cross campus
        if (!$permissions['campusesCreate|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }
        // Templates
        return view('back.envelopes.create', compact('serviceName' , 'campuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title'      => 'required',
                'service'    => 'required',
                'properties' => 'required',
            ]
        );
        if ($envelope = Envelope::create($data)) {
            if(!empty(array_filter($request->campuses))){
                $envelope->campuses()->sync($request->campuses);
            }
            return redirect(route('envelope.index'));

        } else {
            return redirect(back())->withError('Something went wrong!');
        }
    }

    public function show(Envelope $envelope, Request $request)
    {

    }

    public function edit(Envelope $envelope, Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $envelope)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);

        // if user is not permitted to create courses cross campus
        if (!$permissions['campusesEdit|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }
        $envelopeCampuses = $envelope->campuses->pluck('id', 'title')->toArray();

        return view('back.envelopes.edit', compact('envelope' , 'campuses' , 'envelopeCampuses'));
    }


    public function update(Envelope $envelope, Request $request)
    {
        if(!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE , 'edit', $envelope))
        {
            return PermissionHelpers::accessDenied();
        }
        $data = $request->validate(
            [
                'title'      => 'required',
                'service'    => 'required',
                'properties' => 'required',
            ]
        );
        $envelope->update($data);
        if ($envelope->save()) {

            if (!empty(array_filter($request->campuses))) {
                $envelope->campuses()->sync($request->campuses);
            }

            return redirect(route('envelope.index'));
        } else {
            return redirect(back())->withError('Something went wrong!');
        }
    }

    // Clone Envelope
    public function clone(Envelope $envelope, Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $newEnvelope = $envelope->replicate();
        $newEnvelope->title = $envelope->title . " (Clone)";
        $newEnvelope->created_at = Carbon::now();
        $newEnvelope->save();
        return redirect(route('envelope.edit' , ['envelope' => $newEnvelope]));
    }

    public function destroy(Envelope $envelope)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $envelope)) {
            return PermissionHelpers::accessDenied();
        }

        if ($response = $envelope->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $envelope->id],
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

    public function showAddTemplateToEnvelope(Request $request)
    {
        return view('back.envelopes._partials.add-template');
    }

    public function showEditTemplateForm(Envelope $envelope, $templateId, Request $request)
    {
        $templates = json_decode($envelope->properties['templates'], true);

        foreach ($templates as $template) {
            if ($template['id'] == $templateId) {
                $fieldsMapping = json_decode($this->getMappingFields(['hash' => $templateId])->content(), true);
                $fieldsMapping = $fieldsMapping['extra']['html'];
                return view('back.envelopes._partials.edit-template', compact('template', 'fieldsMapping'));
            }
        }
    }

    public function showAddSignerToEnvelope(Request $request)
    {
        return view('back.envelopes._partials.add-signer');
    }

    protected function getApplicationsFields($submissionId = null)
    {
        $fields = Field::with('section.applications')->where('field_type', 'field');

        // Get Fields for a Specific Submission
        if ($submissionId) {
            $sections = Submission::find($submissionId)->application->sections()->pluck('sections.id')->toArray();
            $fields->whereIn('section_id', $sections);
        }
        $fields = $fields->get()->toArray();


        $allowed = ['text' , 'email' , 'date','program', 'course' , 'list','phone' ,'textarea'];
        $list = [];
        foreach ($fields as $field) {

            if (isset($field['section']['applications'][0]) && in_array($field['properties']['type'], $allowed)) {
                $application = $field['section']['applications'][0];
                $list[$application['title']][$field['name']] = $field['label'];

                if(in_array($field['properties']['type'], ['program' , 'course'])){

                    $list[$application['title']][$field['name']."|campusTitle"] = $field['label']." (Campus Title)";

                    $list[$application['title']][$field['name']."|campus"] = $field['label']." (Campus ID)";

                    // Adding Program/Course Start Date
                    $list[$application['title']][$field['name']."|date"] = $field['label']." (Date)";

                    // Adding Program/Course Custom Fields
                    if(isset($field['properties']['customFields'])){
                        foreach ($field['properties']['customFields'] as $customField) {
                            $list[$application['title']][$field['name']."|".$customField['name']] = $field['label']." (" .$customField['name'].")";
                        }
                    }
                }
            }
        }

        return $list;
    }

    public function getMappingFields($payload)
    {
        $list = $this->getApplicationsFields();

        $staticFields = [
            'addons'            => 'Addons',
            'courses_addons'    => 'Course Addons',
            'programs_addons'   => 'Program Addons',
            'course'            => 'Course',
        ];
        if ($templateFields = Sign::getFields($payload['hash'])) {
            $templateFields = array_merge($templateFields, $staticFields);

            $html = view('back.envelopes._partials.fields-mapping', [
                'fieldsSelect' => $list,
                'templateFields' => $templateFields,
            ])->render();

            return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => ['html'  => $html],
                ]);
        } else {
            abort('Missing Required Field', 404);
        }
    }

    public function addMappedField($payload)
    {
        $html = view('back.envelopes._partials.fields-mapping', [
            'field_name'                => $payload['field_name'],
            'field_name_value'          => $payload['field_name_value'],
            'template_field_name'       => $payload['template_field_name'],
        ])->render();

        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html'  => $html],
        ]);
    }

    public function getSignersRows($payload)
    {
        $envelopeSigners = isset($payload['envelopeSigners'])? $payload['envelopeSigners'] : null;
        $html = view('back.envelopes._partials.signer-rows', [
            'envelopeSigners'         => $envelopeSigners,
        ])->render();

        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html'  => $html],
        ]);
    }



    public function getTemplatesRows($payload)
    {
        $envelopeTemplates = isset($payload['envelopeTemplates'])? $payload['envelopeTemplates'] : null;
        if (isset($payload['envelopeId'])) {
            $envelope  = Envelope::findOrfail($payload['envelopeId']);
        } else {
            $envelope = null;
        }
        $html = view('back.envelopes._partials.template-rows', [
            'envelopeTemplates'         => $envelopeTemplates,
            'envelope'                  => $envelope
        ])->render();
        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html'  => $html],
        ]);
    }

    /**
     * Show Select Envelope Modal
     *
     * @param Student $student
     * @param Request $request
     * @return void
     */
    public function showSendEnvelopeForm(Student $student, Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE,
            'campusesView|submission'
        ]);
        $submissions = $student->submissions()->with('application')->byCampus($permissions['campusesView|submission'])->get()->pluck('application.title', 'id')->toArray();
        $envelopes = Envelope::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->pluck('title', 'id')->toArray();
        return view('back.envelopes.send', compact('envelopes', 'student', 'submissions'));
    }

    /**
     * Get A list of Signers for an envelope to assigne names and emails
     *
     * @param [array] $payload
     * @return void
     */
    public function getSignersList($payload)
    {
        if (!$payload['envelope'] || !isset($envelope->properties['signers'])) {
            return [];
        }
        $signersList = [];

        $envelope = Envelope::find($payload['envelope']);
        $signers = json_decode($envelope->properties['signers'], true);

        $fields = $this->getApplicationsFields($payload['submission']);

        if (count($signers)) {
            foreach ($signers as $signer) {
                if ($signer['role'] == 'Student') {
                    $student = Student::find($payload['student_id']);
                    $firstName = $student->first_name;
                    $lastName = $student->last_name;
                    $email = $student->email;
                } else {
                    $firstName = $signer['first_name'];
                    $lastName = $signer['last_name'];
                    $email = $signer['email'];
                }

                $signersList[$signer['order']] = [
                    'first_name'    => $firstName,
                    'last_name'     => $lastName,
                    'email'         => $email,
                    'role'          => $signer['role'],
                ];
            }
        }
        ksort($signersList);

        if (!isset($payload['submission'])) {
            $fields = $this->getApplicationsFields();
        }

        $html = view('back.envelopes._partials.sender-signers-list', compact("signersList", "fields"))->render();

        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['html'  => $html],
        ]);
    }

    /**
     * Review and Send Envelope
     *
     * @param [array] $payload
     * @return void
     */
    public function reviewAndSendEnvelope($payload)
    {
        // Get Envelope Properties
        $envelope = Envelope::find($payload['envelopeId']);
        $properties['templates'] = json_decode($envelope->properties['templates'], true);
        $properties['redirect_url'] = $payload['redirectURL'];

        // Get Student
        $student = Student::find($payload['studentId']);

        // Map Studnet's Data
        $data = $this->getEnvelopeMappedData($properties, $student);
        // create Draft Envelope and Review it
        $response = Sign::generateMultiTemplatesEnvelope($data, $student, $envelope, isset($payload['submissionId']) ?
        $payload['submissionId'] : null ,
        isset($payload['signers']) ? $payload['signers'] : []);

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                'response' => $response,
                'redirect' => route('students.contract.review', ['student' =>$student]),
            ],
        ]);
    }

    /**
     * Get Envelope Mapped Data
     *
     * @param array $properties
     * @param Student $student
     * @return void
     */
    protected function getEnvelopeMappedData($properties, Student $student)
    {
        $submissions = $student->submissions()->orderBy('id', 'desc')->pluck('data')->toArray();

        $fieldGroups = array_column($properties['templates'], 'fields');
        $fields = [];
        foreach ($fieldGroups as $group) {
            $group = json_decode($group, true);
            if(is_array($group)) {
                $group = array_column($group,  'field' , 'Esignature_field');
                $fields += $group;
            }
        }

        $data = [];
        foreach ($submissions as $submission) {
            foreach ($submission as $key => $value) {
                if(is_array($value)) {
                    foreach ($value as $subField => $v) {
                        if($ks = array_keys($fields , $key."|" . $subField )){
                            foreach($ks as $k){
                                $data[$k] = $v;
                            }
                        }elseif($subField == "programs"){
                            if ($ks = array_keys( $fields , $key ) ) {
                                foreach ($ks as $k) {
                                    $data[$k] = $v;
                                }
                            }
                        }
                    }
                }else{
                    if( $ks =  array_keys($fields , $key) ){
                        foreach($ks as $k){
                            $data[$k] = $value;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function reviewContract(Student $student, Request $request)
    {
        $response = json_decode($request->response);

        dd($response);
    }
}
