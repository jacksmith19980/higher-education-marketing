<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Application\ApplicationStatusHelpers;
use App\Helpers\Application\SubmissionHelpers;
use App\Helpers\Permission\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\ApplicationStatus;
use App\Tenant\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Response;

class ApplicationStatusController extends Controller
{
    const PERMISSION_BASE = "stage";


    public function index()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $params = [
            'modelName'   => ApplicationStatus::getModelName(),
        ];

        $statuses = ApplicationStatus::all();

        return view('back.submissions_status.index', compact('statuses', 'params' , 'permissions'));
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }


        return view('back.submissions_status.create');
    }



    public function store(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }


        $request->validate([
            'title' => 'required',
        ]);

        try {
            $application_status = ApplicationStatus::create([
                'title'      => $request->title,
                'label'      => isset($request->label) ? $request->label : '',
            ]);
            $message = ['success' => "Application Status {$application_status->title } created successfully!"];
        } catch (\Exception $exception) {
            $message = ['success' => 'Application Status not created'];
        }

        return redirect(route('applicationStatus.index'))
            ->with($message);
    }


    public function edit(ApplicationStatus $applicationStatus)
    {
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $applicationStatus)) {
            return PermissionHelpers::accessDenied();
        }

        return view('back.submissions_status.edit', compact('applicationStatus'));
    }

    public function update(Request $request, ApplicationStatus $applicationStatus)
    {
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $applicationStatus)) {
            return PermissionHelpers::accessDenied();
        }
        $request->validate([
            'title' => 'required',
        ]);

        $input = $request->only(['title', 'label']);

        try {
            $application_status->fill($input)->save();
            $message = ['success' => "Application Status {$application_status->title } updated successfully!"];
        } catch (\Exception $exception) {
            $message = ['success' => 'Application Status not updated'];
        }


        return redirect(route('applicationStatus.index'))
            ->with($message);
    }

    public function submissionStatusEdit(Submission $submission)
    {
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }


        $route = 'application.status.update';

        $application_status_title = Arr::pluck(ApplicationStatus::select(['title'])->get()->toArray(), 'title');

        $callback = function (string $k): string {
            return ApplicationStatusHelpers::statusLabel($k);
        };

        $application_status_label = array_map($callback, $application_status_title);

        $application_status = array_combine($application_status_title, $application_status_label);

        return view(
            'back.submissions_status._partials.status-edit',
            compact('submission', 'route', 'application_status')
        );
    }

    public function submissionStatusUpdate(Request $request, Submission $submission)
    {
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }


        $request->validate(
            [
                'status' => 'required',
            ]
        );

        SubmissionHelpers::newSubmissionStatus($submission, $request->status, null, auth()->user());

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['student_id' => $submission->statuses->last()],
            ]
        );
    }

    public function submissionStatusActivate(Submission $submission)
    {
        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }

        
        $statuses = array_values($submission->statuses->filter(function($a){
            return $a->status !== 'Archived';
        })->toArray());
        
        SubmissionHelpers::newSubmissionStatus($submission, $statuses[count($statuses) - 1]['status'], null, auth()->user());

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['student_id' => $submission->statuses->last()],
            ]
        );
    }

    public function submissionStatusUpdatePost(Request $request, Submission $submission)
    {
		$permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }


		$filedName = $request->name;

		$submission->data = array_merge($submission->data, [$filedName => $request->value]);
		$status = $submission->data['status'];
		$submission->status = $status;
		$submission->save();

		SubmissionHelpers::newSubmissionStatus($submission, $status, null, auth()->user());

		return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['student_id' => $submission->statuses->last()],
            ]
        );

    }

    public function destroy(ApplicationStatus $applicationStatus)
    {

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $applicationStatus)) {
            return PermissionHelpers::accessDenied();
        }

        if ($response = $applicationStatus->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $applicationStatus->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function getStatusList(Submission $submission)
    {
        $statuses = ApplicationStatusHelpers::getStatuses();
        $statuses = array_combine($statuses, $statuses);
        ksort($statuses);
        return Response::json($statuses);
    }
}
