<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use Hash;
use Mail;
use Storage;
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
use App\Mail\Tenant\UserInvitationEmail;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\Password;
use App\Helpers\Permission\PermissionHelpers;


class ProfileController extends Controller
{
    use HasCampuses;

    const PERMISSION_BASE = "user";

    public function profile()
    {
        $user = Auth::guard('web')->user();

        return view('back.profile.index', compact('user'));
    }

    public function update(Request $request)
    {

        $user = Auth::guard('web')->user();
        if($user->id != $request->userId){
            abort(419);
        }

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
                'position'  => $request->position,
            ];

        if (!empty($request->file('signature'))) {
                if(isset($user->signature)) {
                    Storage::delete($user->signature);
                }
                $data['signature'] = Storage::putFile('/'.session('tenant').'/users/signatures', $request->file('signature'));
        }
        
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

        return redirect(route('user.profile'))->withSuccess('Updated successfully!');
    }

}
