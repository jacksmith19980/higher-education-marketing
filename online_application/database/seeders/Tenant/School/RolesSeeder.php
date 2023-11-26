<?php

namespace Database\Seeders\Tenant\School;

use App\School;
use App\Tenant\Models\Role;
use Illuminate\Database\Seeder;
use Database\Seeders\Tenant\Application\SectionsSeeder;
use Session;
use Auth;

class RolesSeeder extends Seeder
{
    public function __construct()
    {

    }
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $school = School::where('id' , session('school_id'))->first();
        try {
            //code...
            $data = [
                'name'          => 'Super Admin',
                'school_id'     => session('school_id'),
                'full_access'   => true
            ];
            $role = Role::create($data);

            // Add the current User to the role
            $user = Auth::guard('web')->user();
            $user->assignRole($role);
            $user->schools()->save($school);
        } catch (\Exception $e) {

        }
    }
}
