<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use Hash;
use Mail;
use App\User;
use App\School;
use App\Tenant\Manager;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use Illuminate\Validation\Rule;
use App\Rules\School\UniqueUser;
use Illuminate\Http\JsonResponse;
use App\Tenant\Traits\HasCampuses;
use Spatie\Permission\Models\Role;
use App\Helpers\School\SchoolHelper;
use App\Mail\Tenant\UserInvitationEmail;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\Password;
use App\Helpers\Permission\PermissionHelpers;


class UserController extends Controller
{
    use HasCampuses;

    const PERMISSION_BASE = "user";

    /**
     * Show all users
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $school = $this->getSchool();
        if(!$school){
            return view('back.auth.login');
        }


        $params = [
            'modelName' => User::getModelName(),
        ];

        $users = $school->users()->get()->keyBy('id');

        return view('back.users.index', compact('users' , 'params' , 'permissions'));

        // if user is not allowed to view users cross campuses
        if(!$permissions['campusesView|user']){
            $currentUserCampuses = ($this->getUserCampuses()) ? array_column($this->getUserCampuses() , 'id') : [];
            foreach ($users as $user)
            {
                $campusIds = ($user->campuses) ? array_column($user->campuses, 'id') : [];
                !count(array_intersect($campusIds , $currentUserCampuses)) ? $users->forget($user->id) : '';
            }
        }
    }


    /**
     * Show Create User Form
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $school = $this->getSchool();
        if(!$school){
            return view('back.auth.login');
        }

        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null))
        {
            return PermissionHelpers::accessDenied();
        }

        $roles = $school->roles->pluck('name' , 'id')->toArray();

        // if user is not permitted to create courses cross campus
        if (!$permissions['campusesCreate|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }


        return view('back.users.create' , compact('roles' , 'campuses', 'permissions'));
    }


    /**
     * Store New User
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function checkEmailNotExists(Request $request)
    {
        /* if($user = User::whereEmail($request->email)->first()){
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
            ]);
        } */
        return null;
    }

    /**
     * Store New User
     *
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function store(User $user, Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $school = $this->getSchool();

        $request->validate([
                'name'       => 'required|string',
                'role'       => 'required',
                'email'      => 'required|email',
        ]);


        if (!$school) {
            return view('back.auth.login');
        }
        $token    = Str::random(177);
        $password = SchoolHelper::generatePassword(8);
        $teamId = $school->team_id;
        $data = [
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($password),
                'position'          => $request->position,
                'phone'             => $request->phone,
                'activation_token'  => $token,
                'team_id'           => $teamId,
        ];

        // Check if the user is already in the school
        $user = User::firstOrCreate([
            'email' => $request->email
        ] , $data);

        if($request->filled('campuses') && count(array_filter($request->campuses))){
            $user->saveCampuses($request->campuses);
        }

        $role = Role::findOrFail($request->role);
        $user->assignRole($role);
        $user->schools()->save($school);

        // Send Invitation Email
        $users[$request->email] = $data;

        // override the hashed password
        $data['password'] = $password;
        Mail::to(array_keys($users))->send(new UserInvitationEmail($data, $school));

        return redirect(route('users.index'))->withSuccess('the user was created successfully!');
    }


    /**
     * Show Edit Form
     *
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function edit(User $user, Request $request)
    {

        if(!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $user))
        {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);


        $school = $this->getSchool();
        if(!$school){
            return view('back.auth.login');
        }
        $roles = $school->roles->pluck('name' , 'id')->toArray();

        // if user is not permitted to create courses cross campus
        if (!$permissions['campusesEdit|' . self::PERMISSION_BASE]) {
            $campuses = $this->getUserCampusesList();
        } else {
            $campuses = $this->getCampusesList();
        }
        return view('back.users.edit' , compact( 'user' , 'roles' , 'campuses'));
    }

    /**
     * Update User
     *
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function update(User $user, Request $request)
    {
        $user = User::find($request->userId);
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $user)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);

        $request->validate([
               'name'       => ['required','string'],
               'email'      => ['required','email', new UniqueUser($user) ],
        ]);

        $data = [
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'position'  => $request->position
            ];

        if (isset($request->password)) {
            $data['password'] = Hash::make($request->password);
        }
        if($request->filled('campuses')){
            // Delete Old Campuses
            $user->detachCampuses();
            // Save New Campuses
            $user->saveCampuses($request->campuses);
        }
        if($request->filled('role')){
            // Assign Roles
            $role = Role::findOrFail($request->role);
            $user->syncRoles($role);
        }

        if($request->filled('language')){
            $settings = json_decode($user->settings , true);
            $settings['language'] = $request->language;
            $data['settings'] = $settings;
        }

        // Update User
        $user->update($data);

        return redirect(route('users.index'))->withSuccess('Updated successfully!');
    }

    public function destroy(User $user, Request $request)
    {

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $user)) {
            return PermissionHelpers::accessDenied();
        }
        $permissions =  PermissionHelpers::areGranted([
            'delete|' . self::PERMISSION_BASE,
        ]);

        if ($response = $user->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $user->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
}

    }

    public function getSettings(Request $request)
    {
        return Response::json(auth()->user()->settings ? json_decode(auth()->user()->settings) : []);
    }

    public function updateSettings(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $settings = (array)json_decode($user->settings);
        $settings[$request->category] = array_map(fn($a) => (int)$a, $request->settings);
        $user->settings = $settings;
        $user->save();
        Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [],
        ]);
    }
}
