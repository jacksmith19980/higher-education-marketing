<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use App\Tenant\Models\Submission;
use App\Http\Controllers\Controller;
use App\Helpers\Application\SubmissionHelpers;
use App\Http\Controllers\Api\Filters\HasApiFilters;

class SubmissionsApiController extends Controller
{
    use ApiResponseHelpers, HasApiFilters;

    public function index(Request $request)
    {
        $submissions = new Submission;

        if ($request->has('filters')) {
            $submissions = $this->filter($request->filters, $submissions);
        }

        try {
            $submissions = $submissions->with([
                'student',
            ])->orderBy('id', 'ASC')->paginate();
            return $this->respondWithSuccess([
                'data' => $submissions
            ]);
        } catch (\Exception $e) {
            return $this->respondError("Invalid Filters");
        }
    }

    public function show($school, $id = null, Request $request)
    {
        $submission = Submission::with('statuses', 'student')->find($id);
        return $this->respondWithSuccess(
            [
                'data' => [
                    'submission' => $submission
                ]
            ]
        );
    }
    public function update($school, $id = null, Request $request)
    {
        $submission = Submission::findOrFail($id);
        $orignalData = $submission->data;
        $data = $request->data;

        // Update Submission Status TimeLine
        if ($request->has('status')) {
            SubmissionHelpers::updateSubmissionStatus(
                $submission,
                $request->status,
                [
                    'submitted_by'  => 'API',
                    'name'          => 'API',
                    'submitted_at'  => Carbon::now(),
                ]
            );

            // Add the Status to the Data if Exist
            $data['status'] = $request->status;
        }
        if ($data) {
            $data = array_replace($orignalData, $data);
        }
        $submission->update([
            'status' => $request->status,
            'data'   => $data,
        ]);

        return $this->respondWithSuccess(
            ['data' => [
                'submission' => $submission->load('statuses', 'student')
            ]]
        );
    }
}
