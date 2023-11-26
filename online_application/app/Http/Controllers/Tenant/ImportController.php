<?php

namespace App\Http\Controllers\Tenant;

use Str;
use Storage;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use App\Helpers\customfield\CustomFieldHelper;


class ImportController extends Controller
{


    public function upload(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('back.import.index');
        }

        if ($request->method() == 'POST') {

            $headers = [];
            $data = [];
            $path = null;

            if ($request->has('contact_import.file')) {

                // upload the file to Storage
                if ($url = Storage::putFile('/' . session('tenant') . '/import', $request->contact_import['file'])) {

                    // Create a Temporary FilePath
                    list($headers, $data) = $this->parseFile($url);

                    $customFields = CustomFieldHelper::getContactsCustomFields('name', 'slug');

                    $fields = $this->getContactFields();
                    $defaultFields = $this->getDefaultFields();

                    $fields = $fields +  $defaultFields  + $customFields;

                    return view('back.import.import', compact('headers', 'data', 'path', 'url', 'fields'));
                }

                return view('back.import.index');
            }
        }
    }

    public function import(Request $request)
    {
        list($headers, $data) = $this->parseFile($request->file);
        $fields = array_keys($this->getContactFields());
        $default = array_keys($this->getDefaultFields());
        $customFields = array_keys(CustomFieldHelper::getContactsCustomFields('name', 'slug'));

        foreach ($data as $items) {
            $contact = [];
            foreach ($items as $key => $value) {

                if (isset($request->map[$key])) {

                    if (in_array($request->map[$key], $customFields)) {

                        $contact['student']['properties']['customfields'][$request->map[$key]] = $value;
                    } elseif (in_array($request->map[$key], $fields)) {
                        $contact['student'][$request->map[$key]] = $value;
                    } else {
                        $contact['defaults'][$request->map[$key]] = $value;
                    }
                }
            }
            // Create the contact
            $contacts[] = $this->createContact($contact);
        }

        return view('back.import.result', compact('contacts'));
    }

    protected function createContact($contact = null)
    {

        if (!count(array_filter($contact))) {
            return null;
        }

        if (isset($contact['student']['email'])) {
            $student = Student::updateOrCreate(
                ['email' => $contact['student']['email']],
                $contact['student']
            );
        } else {
            $student = Student::create($contact['student']);
        }

        if (isset($contact['defaults']['campus'])) {
            $campus = Campus::where('id', $contact['defaults']['campus'])->orWhere('slug', $contact['defaults']['campus'])->orWhere('title', $contact['defaults']['campus'])->first();
            $student->campuses()->sync($campus);
        }

        return $student;
    }

    protected function getContactFields()
    {
        $excludes = ["id", "agent_id", "parent_id", "password", "avatar", "remember_token", "created_at", "updated_at", "params"];

        $studentsTable = collect(Schema::connection('tenant')->getColumnListing('students'));
        $list = [];

        foreach ($studentsTable as $field) {
            if (!in_array($field, $excludes)) {
                $list[$field] = Str::title(str_replace('_', ' ', $field));
            }
        }
        return $list;
    }
    protected function getDefaultFields()
    {
        return [
            'stage'     => 'Contact Type',
            'campus'    => 'Campus',
        ];
    }

    protected function parseFile($url)
    {
        $path = Storage::disk('s3')->temporaryUrl($url, \Carbon\Carbon::now()->addMinutes(5));
        // Pars the CSV file
        list($headers, $data) = $this->csvToArray($path);
        return [$headers, $data];
    }

    protected function csvToArray($filename = '', $delimiter = ',', $limit = 1000)
    {
        $headers = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, $limit, $delimiter)) !== false) {
                if (!$headers)
                    $headers = $row;
                else
                    $data[] = (count($headers) == count($row)) ? array_combine($headers, $row) : $row;
            }
            fclose($handle);
        }
        return [$headers, $data];
    }
}
