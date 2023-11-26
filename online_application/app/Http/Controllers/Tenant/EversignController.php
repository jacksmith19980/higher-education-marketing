<?php

namespace App\Http\Controllers\Tenant;

use App\Actions\Action;
use App\Helpers\cart\CartHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\ApplicationAction;
use App\Tenant\Models\Cart;
use App\Tenant\Models\Field;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\StudentAction;
use App\Tenant\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Response;
use Sign;

class EversignController extends Controller
{
    protected $addonsLoop = [];
    protected $coursesLoop = [];
    protected $cart;

    public function eversign(School $school, Application $application, Request $request)
    {
        $field = Field::find($request->field_id);
        $user = Auth::guard('student')->user();

        //@TODO Will be changed when enabling multi application submissions
        $submission = $user->submissions()->where('application_id', $application->id)->first();

        $map = $field->properties['custom']['fields'];
        // Get Submitted Fields Value
        $fieldsData = $this->getSubmittedFieldsValues($map, $submission, $user, $application);

        $props = [
            'documentHash'  => $field['properties']['documentHash'],
            'documentTitle' => $field->label,
            'documentMessage' => 'This is the inscription contract',
            'enableSchoolSignature' => isset($field['properties']['enableSchoolSignature']) ? true : false,
            'schoolSignerName'  => $field['properties']['schoolSignerName'],
            'schoolSignerEmail'  => $field['properties']['schoolSignerEmail'],
        ];

        if ($request->document_hash == null) {
            // Create New Document
            $document = Sign::createDocument($props, $fieldsData);
        } else {
            // Update Document
            $document = Sign::updateDocument($props, $fieldsData, $request->document_hash);
        }
        if ($document) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => [
                    'documentURL'   => $document['documentURL'],
                    'documentHash'  => $document['documentHash'],
                ],
            ]);
        }

        return abort('Something went wrong', 500);
    }

    public function afterEversign(School $school, Application $application, Submission $submission, ApplicationAction $action, Invoice $invoice, Request $request)
    {
        $user = Auth::guard('student')->user();
        $map = $action->properties['custom_fields'];

        $fieldsData = $this->getSubmittedFieldsValues($map, $submission, $user, $application);

        $props = [
            'documentHash'  => $action['properties']['documentHash'],
            'documentTitle' => $action->label,
            'documentMessage' => 'This is the inscription contract',
            'enableSchoolSignature' => isset($field['properties']['enableSchoolSignature']) ? true : false,
            'schoolSignerName'  => $action['properties']['schoolSignerName'],
            'schoolSignerEmail'  => $action['properties']['schoolSignerEmail'],
        ];

        if ($request->document_hash == null) {
            // Create New Document
            $document = Sign::createDocument($props, $fieldsData);
        } else {
            // Update Document
            $document = Sign::updateDocument($props, $fieldsData, $request->document_hash);
        }
        if ($document) {
            return view('front.applications.eversign')->with([
                'documentURL' => $document['documentURL'],
                'application' => $application,
                'action' => $action,
                'invoice' => $invoice,
                'submission' => $submission,
             ]);
        }

        return abort('Something went wrong', 500);
    }

    public function signed(School $school, Application $application, Submission $submission)
    {
        $data = $submission->data;

        $data['document_signed_at'] = 1;

        $submission->data = $data;

        $submission->save();

        return Response::json([
                  'status'    => 200,
                  'response'  => 'success',
                  'extra'     => [
                      $submission->data,
                  ],
              ]);
    }

    /**
     * Extract Values for EverSign PDF
     * @param $map [field, eversign_field]
     * @param $data [application_field => value]
     * @return array
     */
    protected function getSubmittedFieldsValues($map, $submission, $user, $application)
    {
        $data = $submission->data;

        $map = json_decode($map, true);

        $values = [];

        if ($map && is_array($map)) {
            foreach ($map as $field) {
                $appField = explode('|', $field['field']);

                if (count($appField) == 1) {
                    if (isset($field['eversign_field']) && isset($data[$field['field']])) {
                        $values[$field['eversign_field']] = $data[$appField[0]];
                    }
                } elseif (isset($field) && array_key_exists('eversign_field', $field)) {
                    $values[$field['eversign_field']] = $this->getCartData($submission, $appField, $field['eversign_field'], $user, $application);
                }
            }
        }
        // Process the Addons Data
        if (count($this->addonsLoop)) {
            $values = $this->extractAddonsData($values);
        }

        $values = $this->extractCoursesData($values);

        return $values;
    }

    /** Map Cart Data */
    protected function getCartData($submission, $appField, $eversignField, $user, $application)
    {
        $this->cart = Cart::where(['application_id' => $application->id, 'student_id' => $user->id])->first();

        switch ($appField[0]) {
            case 'programs':
                return $this->extractProgramData($this->cart, $appField[1]);
                break;
            case 'cart':
                return $this->extractCartData($this->cart, $appField[1]);
                break;
            case 'addons':
                // Isolate the addons to it's owne array
                $this->addonsLoop[$appField[1]][] = $eversignField;
                break;
            case 'courses':
                $this->coursesLoop[$appField[1]][] = $eversignField;
                break;
        }
    }

    /** Extract addons Data */
    protected function extractAddonsData($values)
    {
        if (array_key_exists('addons', $this->cart->data)) {
            $addons = $this->cart->data['addons'];
            foreach ($addons as $key => $addon) {
                foreach ($addon as $k => $v) {
                    if (isset($this->addonsLoop[$k][$key])) {
                        $values[$this->addonsLoop[$k][$key]] = $v;
                    }
                }
            }
        }

        return $values;
    }

    /** Extrat Cart Totals */
    protected function extractCartData($cart, $key)
    {
        $price = CartHelpers::getCartTotalPrice($cart->data);
        switch ($key) {
            case 'total':
                return $price['total'];
                break;

            case 'programs':
                return $price['programs'];
                break;

            case 'courses':
                return $price['courses'];
                break;

            case 'addons':
                return $price['addons'];
                break;
        }
    }

    /** Extract Programs Data */
    protected function extractProgramData($cart, $key)
    {
        // Get the program @TODO Change when having multiple
        $program = $cart->data['programs'];

        if (isset($program[$key])) {
            return $program[$key];
        } else {
            $tmp = explode('_', $key);

            if ($tmp[0] == 'addons') {
                // Get the Addon @TODO Change when having multiple
                $addon = reset($program['addons']);

                return $addon[$tmp[1]];
            }
        }

        return '';
    }

    private function extractCoursesData(array $values)
    {
        if (count($this->coursesLoop)) {
            $courses = $this->cart->data['courses'];
            foreach ($courses as $key => $course) {
                foreach ($course as $k => $v) {
                    if (isset($this->coursesLoop[$k][$key])) {
                        $values[$this->coursesLoop[$k][$key]] = $v;
                    }
                }

                if (array_key_exists('addons', $course)) {
                    foreach ($course['addons'] as $key2 => $addon) {
                        foreach ($addon as $k => $v) {
                            if ($k === 'key') {
                                $j = 'title';
                            } else {
                                $j = $k;
                            }
                            if (isset($this->coursesLoop['addons_'.$j][$key2])) {
                                $values[$this->coursesLoop['addons_'.$j][$key2]] = $v;
                            }
                        }
                    }
                }
            }
        }

        return $values;
    }
}
