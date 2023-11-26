<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Helpers\Permission\PermissionHelpers;
use App\Tenant\DocumentBuilder;
use App\Tenant\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PdfService;
use App\Tenant\Models\CustomField;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Shareable;
use App\Tenant\Models\Student;
use Response;
use DB;
use Illuminate\Support\Facades\Auth;
use GPBMetadata\Google\Type\Datetime;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentBuilderController extends Controller
{
    const  PERMISSION_BASE = "documentBuilder";

    private $manyToManyRelations = [
        'students' => [
            [
                'target' => 'groups',
                'link' => 'group_student'
            ]
        ]
    ];

    /** @var PdfService */
    private $pdfService;

    public function __construct(
        PdfService $pdfService
    ) {
        $this->pdfService = $pdfService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE,
            'delete|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $documentBuilders = DocumentBuilder::orderBy('id', 'DESC')->get();

        $params = [
            'modelName' => 'documentBuilder',
        ];

        return view('back.document-builder.index', compact('documentBuilders', 'permissions', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $tables = [
            'students'      => 'Students',
            'applications'  => 'Applications',
            'courses'       => 'Courses',
            'programs'      => 'Programs',
            'submissions'   => 'Submissions'
        ];

        return view('back.document-builder.create', compact('tables'));
    }

    /**
     * get PDF Infos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPdfInfos(Request $request)
    {
        $validator = validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg,jpeg,csv,txt,pdf|max:5048'
        ]);

        if ($validator->fails()) {
            return Response::json(
                [
                    'status'   => 419,
                    'response' => 'faild',
                    'extra'    => ['message' => $validator->errors()->first('file')],
                ]
            );
        } else {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filename = Storage::disk('local')->put('/'.session('tenant').'/documentBuilder/pdfs', $request->file('file'));
            $pdfInfos = $this->pdfService->readInfo($filename);
            Storage::disk('local')->delete($filename);
            $filename = Storage::putFile('/'.session('tenant').'/documentBuilder/pdfs', $request->file('file'));
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'tables'                    => $this->getTables($request->table),
                        'title'                     => $pdfInfos->title,
                        'filename'                  => $filename,
                        'textFields'                => $pdfInfos->formFields->textFields,
                        'choiceFields'              => $pdfInfos->formFields->choiceFields,
                        'signatureFields'           => $pdfInfos->formFields->signatureFields
                    ]
                ]
            );
        }
    }

    /**
     * get PDF Infos
     *
     * @param  string  $table
     * @return array $columns
     */
    public function getTables(string $table): array
    {
        $relations = [
            'students' => ['students'=>'Contacts', 'submissions'=>'Submissions', 'programs'=>'Programs', 'groups'=>'Groups', 'invoices'=>'Invoices', 'user'=>'User'],
            'instructors' => ['instructors'=>'Instructors', 'courses'=>'Courses', 'groups'=>'Groups', 'user'=>'User'],
        ];

        return isset($relations[$table]) ? $relations[$table] : [];
    }

    /**
     * get PDF Infos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFields($data)
    {
        $columns = [];

        if ($data['table']  === 'settings') {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['columns' => Setting::get('slug')->map(function ($a) {
                        return [
                            'name' => $a['slug'],
                            'label' => $a['slug']
                        ];
                    })->toArray()],
                ]
            );
        } elseif ($data['table']  === 'special') {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['columns' => [
                        [
                            'name' => 'date',
                            'label' => 'date'
                        ]
                    ]],
                ]
            );
        } elseif ($data['table']  === 'user') {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['columns' => [
                        [
                            'name' => 'name',
                            'label' => 'Name'
                        ],
                        [
                            'name' => 'email',
                            'label' => 'Email'
                        ],
                        [
                            'name' => 'position',
                            'label' => 'Position'
                        ],
                        [
                            'name' => 'signature',
                            'label' => 'Signature'
                        ]
                    ]],
                ]
            );
        } else {

            $dbColumns = DB::connection('tenant')->select('DESCRIBE `' . $data['table'] . '`');

            $customFields = CustomField::where('properties', $data['table'])->get();

            //$dbCustomColumns =  

            $excludedColumns = ['updated_at', 'password', 'remember_token', 'deleted_at', 'updated_at', 'parent_id'];


            foreach ($dbColumns as $key => $value) {
                if (!in_array($value->Field, $excludedColumns)) {
                    $columns[] = [
                        'name' => $value->Field,
                        'label' => $this->getTableFieldName($value->Field, $data['table'])
                    ];
                }
            }

            if(count($customFields)>0) {
                foreach ($customFields as $customField) {
                    if (!in_array($customField->name, $excludedColumns)) {
                        $columns[] = [
                            'name' => $customField->name,
                            'label' => $this->getTableFieldName($customField->name, $data['table'])
                        ];
                    }
                }
            }

            if ($data['table'] === 'students') {
                $columns[] = [
                    'name' => 'fullName',
                    'label' => __('Full Name')
                ];
            }
        }
        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['columns' => $columns],
            ]
        );
    }

    /**
     * Get Field Name from custom field table
     *
     * @param  string $field
     * @param  string $table
     * @return string
     */
    public function getTableFieldName(string $field, string $table): string
    {
        $customField = CustomField::where('slug', $field)->where('properties', $table)->first();

        if ($customField) {
            return $customField->name;
        }

        return ucfirst(str_replace('_', ' ', $field));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $fields = [];
        $i = 0;

        for (;;) {
            $name = "name-" . $i;
            $table = "table-" . $i;
            $value = "value-" . $i;
            $text = "text-" . $i;
            $format = "format-" . $i;
            $choices = "choices-" . $i;
            if (!isset($request->$name)) {
                break;
            }
            $fields[] = [
                'name' => $request->$name,
                'type' => (is_null($request->$table) and $request->$value) ? 'choices' : 'text',
                'choices' => (is_null($request->$table) and $request->$value) ? explode(',', $request->$choices) : []
            ];
            $data[] = [
                "name" =>  $request->$name,
                'type' => (is_null($request->$table) and $request->$value) ? 'choices' : (in_array($request->$table, ['custom', 'special']) ? $request->$table : 'data'),
                'value' => (is_null($request->$table) and $request->$value) ? $request->$value : (($request->$table and $request->$value) ? $request->$table . '.' . $request->$value : $request->$text),
                "format" =>  $request->$format,
                'choices' => (is_null($request->$table) and $request->$value) ? explode(',', $request->$choices) : []
            ];
            $i += 1;
        }

        $DocumentBuilder  = new DocumentBuilder();
        $DocumentBuilder->name = $request->all()['pdf-title'];
        $DocumentBuilder->description = $request->all()['description'];
        $DocumentBuilder->document = $request->all()['pdf-filename'];
        $DocumentBuilder->selector = $request->all()['selector-table'];
        $DocumentBuilder->fields = json_encode($fields);
        $DocumentBuilder->properties = json_encode($data);
        $DocumentBuilder->save();

        return redirect(route('documentBuilder.index'))->withSuccess('Document Builder was created successfully!');
    }

    /**
     * Build Document.
     *
     * @param  \App\DocumentBuilder  $DocumentBuilder
     * @return \Illuminate\Http\Response
     */
    public function buildDocument(DocumentBuilder $documentBuilder, int $id)
    {
        $data = [];
        $columns = [];
        $settings = [];
        $tables = [];

        $properties = json_decode($documentBuilder->properties);
        $settingData = Setting::get(['slug', 'data'])->toArray();

        foreach ($properties as $property) {
            if ($property->type === 'data') {
                if ($property->value and (explode('.', $property->value)[1] !== 'properties')) {
                    if (explode('.', $property->value)[0] == 'settings') {
                        $settings[] = explode('.', $property->value)[1];
                    } elseif (explode('.', $property->value)[0] == 'special') {
                    } else {
                        $columns[] = $property->value;
                    }
                    $table = explode('.', $property->value)[0];
                    if ($table !== $documentBuilder->selector and $table !== 'settings' and $table !== 'special') {
                        $tables[] = $table;
                    }
                }
            }
        }

        $tables = array_unique($tables);
        $userIndex = array_search('user', $tables);
        if ($userIndex !== false) {
            unset($tables[$userIndex]);
        }
        $tables = array_values($tables);

        $dbColumns = DB::connection('tenant')->table($documentBuilder->selector);

        $customFields = CustomField::where('properties', $documentBuilder->selector)->get()->pluck('name')->toArray();
        $customFieldsWithSlug = CustomField::where('properties', $documentBuilder->selector)->get()->pluck('slug','name')->toArray();

        foreach ($tables as $table) {
            if ($documentBuilder->selector === 'students' and $table === 'programs' and $table === 'user') {
                continue;
            }
            $dbColumns->join($table, $documentBuilder->selector . '.id', '=', $table . '.' . substr($documentBuilder->selector, 0, -1) . '_id');
        }

        if ($documentBuilder->selector === 'students' and in_array('groups', $tables)) {
            $dbColumns->join('group_student', 'students.id', '=', 'group_student.student_id');
            $dbColumns->join('groups', 'group_student.group_id', '=', 'groups.id');
            if (in_array('programs', $tables)) {
                $dbColumns->join('programs', 'groups.program_id', '=', 'programs.id');
            }
        }

        //$dbColumns->select(array_map(fn ($a) => $a === 'students.fullName' ? DB::raw("CONCAT(`first_name`, ' ', `last_name`) AS `students.fullName`") : (in_array(substr($a, strpos($a, '.') + 1), $customFields) ? DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`properties`, '$.customfields." . Str::slug(substr($a, strpos($a, '.') + 1)) . "')) AS `$a`") : "`$a`"), $columns));
        //$dbColumns->select(array_map(fn ($a) => $a === 'students.fullName' ? DB::raw("CONCAT(`first_name`, ' ', `last_name`) AS `students.fullName`") : $a . ' AS ' . $a, $columns));
        //dd($customFields, Str::slug(substr($columns[0], strpos($columns[0], '.') + 1)));

        $nonExistingValues = array_diff($columns, ['user.name', 'user.email', 'user.position', 'user.signature']);

        $userColumns = array_intersect($columns, ['user.name', 'user.email', 'user.position', 'user.signature']);
        
        //dd($customFields, $nonExistingValues, );

        $dbColumns->select(array_map(fn ($a) => 
            $a === 'students.fullName' ? DB::raw("CONCAT(`first_name`, ' ', `last_name`) AS `students.fullName`") : 
            ((in_array(substr($a, strpos($a, '.') + 1), $customFields) ? DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`properties`, '$.customfields." . $customFieldsWithSlug[substr($a, strpos($a, '.') + 1)] . "')) AS `" . $a . "`")
            : $a . ' AS ' . $a ) ), $nonExistingValues));

        $dbColumns->where($documentBuilder->selector . '.id', $id);
        // dd($dbColumns->toSql());
        $dbData = $nonExistingValues ? (array)$dbColumns->get()[0] : [];

        if(count($userColumns)>0) {
            foreach ($userColumns as $column) {
                $user = Auth::guard('web')->user();
                $value = '';
                switch ($column) {
                    case 'user.email':
                        $value = $user->email;
                        break;
                    case 'user.name':
                        $value = $user->name;
                        break;
                    case 'user.position':
                        $value = $user->position;
                        break;
                    case 'user.signature':
                        $value = $user->signature;
                        break;
                    default:
                        break;
                }
                $dbData[$column] = $value;
            }
        }

        foreach ($properties as $property) {
            // dd($property);
            if ($property->type === 'data') {
                if ($property->value and (explode('.', $property->value)[1] !== 'properties')) {
                    if (explode('.', $property->value)[0] === 'settings') {
                        $data[] = [
                            'name' => $property->name,
                            'value' => $this->formatData(array_values(array_filter($settingData, fn ($a) => $a['slug'] === explode('.', $property->value)[1]))[0]['data'], $property->format)
                        ];
                    } else {
                        if($property->value == "students.phone") {
                            $phoneData = json_decode($dbData[strtolower($property->value)], true);
                            $data[] = [
                                'name' => $property->name,
                                'value' => $this->formatData($phoneData["phone"], $property->format)
                            ];
                        } elseif($property->value == "user.signature") {
                            $data[] = [
                                'name' => $property->name.'|SignatureField',
                                'value' => $this->formatData($dbData[$property->value], $property->format)
                            ];
                        } else {
                            $data[] = [
                                'name' => $property->name,
                                'value' => $this->formatData($dbData[$property->value], $property->format)
                            ];
                        }
                    }
                }
            } elseif ($property->type === 'special') {
                $data[] = [
                    'name' => $property->name,
                    'value' => $this->getSpecialFields('date', $property->format)
                ];
            } else {
                $data[] = [
                    'name' => $property->name,
                    'value' => $property->value
                ];
            }
        }

        $resultData = [];

        // Iterate through the original array
        foreach ($data as $item) {
            $resultData[$item['name']] = $item['value'];
        }
        
        $file = $this->pdfService->writeInfo($documentBuilder->document, $resultData);

        $shareable = Shareable::where('documentable_type', 'App\Tenant\DocumentBuilder')->where('documentable_id', $documentBuilder->id)->first();
        if ($shareable) {
            $properties = json_decode($shareable->properties);
            if (is_null($properties)) {
                $properties = new \stdClass();
            }
            $properties->read = true;
            $shareable->properties = json_encode($properties);
            $shareable->save();
        }

        return $file;
    }

    private function getSpecialFields(string $field, $format): string
    {
        $format = (isset($format) && ($format == 'd-m-Y' || $format == 'Y-m-d')) ? $format : 'd-m-Y';
        switch ($field) {
            case 'date':
                return (new \Datetime())->format($format);
                break;

            default:
                return '';
                break;
        }
    }

    private function formatData(?string $data, ?string $format): string
    {
        if (!$data)
            return '';

        if (!$format)
            return $data;

        switch ($format) {
            case 'uppercase':
                return strtoupper($data);
                break;
            case 'lowercase':
                return strtolower($data);
                break;
            case 'capitalize':
                return ucfirst($data);
                break;

            default:
                return '';
                break;
        }
    }

    /**
     * Showing the form to share document with students
     */
    public function showShareDocument(Student $student)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        $documents = DocumentBuilder::pluck('name', 'id')->toArray();

        foreach ($documents as $key => $document) {
            # code...
            $shareable = Shareable::where('documentable_type', DocumentBuilder::class)->where('documentable_id', $key)->where('shareable_id', $student->id)->first();
            if ($shareable) {
                $documents[$key] = $documents[$key] . ' (' . __('Shared') . ')';
            } else {
                $documents[$key] = $documents[$key];
            }
        }

        return view('back.document-builder.share', compact('documents', 'student'));
    }

    /**
     * Save Sharing document with student
     */
    public function shareDocument($payload)
    {
        $payload['document'];
        $payload['student'];
        $student = Student::find($payload['student']);
        $document = DocumentBuilder::find($payload['document']);

        $shareable = Shareable::where('documentable_type', DocumentBuilder::class)->where('documentable_id', $document->id)->where('shareable_id', $student->id)->first();
        if (!$shareable) {
            Shareable::create([
                'shareable_id' => $student->id,
                'shareable_type' => Student::class,
                'documentable_id' => $document->id,
                'documentable_type' => DocumentBuilder::class,
                'is_active' => 1
            ]);

            $html = view('back.students._partials.student-shareables-div', [
                'shareables' =>  $student->shareables,
            ])->render();

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'message'    => __('Shared Successfully!'),
                        'html' => $html
                    ],
                ]
            );
        }

        return Response::json(
            [
                'status'   => 400,
                'response' => 'error',
                'extra'    => [
                    'message'    => __('Already Shared')
                ],
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DocumentBuilder  $DocumentBuilder
     * @return \Illuminate\Http\Response
     */
    public function viewDocumentBuilder($payload)
    {
        $documentBuilder = DocumentBuilder::find($payload['document']);
        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                    'document'    => $documentBuilder
                ],
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DocumentBuilder  $DocumentBuilder
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentBuilder $DocumentBuilder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DocumentBuilder  $DocumentBuilder
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentBuilder $documentBuilder)
    {
        $tables = $this->getTables($documentBuilder->selector);
        $columns = [];

        return view('back.document-builder.edit', compact('tables', 'columns', 'documentBuilder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DocumentBuilder  $documentBuilder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentBuilder $documentBuilder)
    {
        $data = [];
        $fields = [];
        $i = 0;

        for (;;) {
            $name = "name-" . $i;
            $table = "table-" . $i;
            $value = "value-" . $i;
            $text = "text-" . $i;
            $format = "format-" . $i;
            $choices = "choices-" . $i;
            if (!isset($request->$name)) {
                break;
            }
            // dump($request->$name, $request->$table, $request->$value, $request->$format);
            $fields[] = [
                'name' => $request->$name,
                'type' => (is_null($request->$table) and $request->$value) ? 'choices' : 'text',
                'choices' => (is_null($request->$table) and $request->$value) ? explode(',', $request->$choices) : []
            ];
            $data[] = [
                "name" =>  $request->$name,
                'type' => (is_null($request->$table) and $request->$value) ? 'choices' : (in_array($request->$table, ['custom', 'special']) ? $request->$table : 'data'),
                'value' => (is_null($request->$table) and $request->$value) ? $request->$value : (($request->$table and $request->$value) ? $request->$table . '.' . $request->$value : $request->$text),
                "format" =>  $request->$format,
                'choices' => (is_null($request->$table) and $request->$value) ? explode(',', $request->$choices) : []
            ];

            $i += 1;
        }

        $documentBuilder->name = $request->all()['pdf-title'];
        $documentBuilder->description = $request->all()['description'];
        $documentBuilder->selector = $request->all()['selector-table'];
        $documentBuilder->fields = json_encode($fields);
        $documentBuilder->properties = json_encode($data);
        $documentBuilder->save();

        return redirect(route('documentBuilder.index'))->withSuccess('Document Builder was updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DocumentBuilder  $documentBuilder
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentBuilder $documentBuilder)
    {
        //
        $response = $documentBuilder->delete();

        if ($response) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $documentBuilder->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function bulkDestroy(Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', null)) {
            return PermissionHelpers::accessDenied();
        }

        $reports = [];
        if (isset($request->selected) and isset($request->selected[0]['selected'])) {
            $reports = DocumentBuilder::whereIn('id', array_map(fn ($a) => $a['selected'], $request->selected));
        } else {
            $reports = DocumentBuilder::whereIn('id', $request->selected);
        }

        if ($reports->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'data_table' => 'students_datatable',
                        'message'    => __('Deleted Successfully!')
                    ],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 419,
                    'response' => 'faild',
                    'extra'    => ['message' => 'Something went wrong!'],
                ]
            );
        }
    }

    public function getSharedDocument($payload)
    {
        if (!$payload['shareableId']) {
            return Response::json([
                'status'    => 404,
                'response'  => 'failed',
                'extra'     => 'Shared document not found',
            ]);
        }

        $shareable = Shareable::find($payload['shareableId']);
        $documentBuilder = DocumentBuilder::find($shareable->documentable_id);
        $document = $this->buildDocument($documentBuilder, $shareable->shareable_id);

        $filename = $document['name'];
        if($shareable->shareable_type == 'App\Tenant\Models\Student') {
            $student = Student::find($shareable->shareable_id);
            $filename = $student->name.' - '.$documentBuilder->name.'.pdf';
        }

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                'student_id' => $shareable->shareable_id,
                'document_id' => $shareable->documentable_id,
                'option' => $payload['option'],
                'FileContent'   => base64_encode($document['content']),
                'ContentType'   => 'application/pdf',
                'FileName'      => $filename
            ],
        ]);
    }

}
