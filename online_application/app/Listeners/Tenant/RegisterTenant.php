<?php

namespace App\Listeners\Tenant;

use Crypt;
use App\School;
use App\Tenant\Manager;
use App\Tenant\Models\Role;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Setting;
use Illuminate\Support\Facades\Schema;
use App\Events\Tenant\TenantIdentified;
use App\Tenant\Database\DatabaseManager;
use Illuminate\Database\Schema\Blueprint;
use Auth;

class RegisterTenant
{
    protected $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Handle the event.
     *
     * @param  TenantIdentified  $event
     * @return void
     */
    public function handle(TenantIdentified $event)
    {
        app(Manager::class)->setTenant($event->tenant);
        $this->db->createConnection($event->tenant);
        $connection = $event->tenant->tenantConnection;
        // Find the School
        if ($connection->version <= 1) {
            if (!Schema::connection('tenant')->hasTable('submission_statuses')) {
                // Add New Migrations here
                Schema::connection('tenant')->create('submission_statuses', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('student_id')->unsigned()->nullable();
                    $table->integer('submission_id')->unsigned()->nullable();
                    $table->string('status');
                    $table->text('properties')->nullable();
                    $table->timestamps();
                });

                $connection->version++;
                $connection->save();
            }
        }

        if ($connection->version <= 2) {
            if (!Schema::connection('tenant')->hasTable('invoice_payments')) {
                // Add New Migrations here
                Schema::connection('tenant')->create('invoice_payments', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('uid', 100);
                    $table->float('amount_paid');
                    $table->string('status', 50);
                    $table->string('payment_gateway')->nullable();
                    $table->string('payment_method')->nullable();
                    $table->longText('properties')->nullable();
                    $table->integer('invoice_id')->unsigned()->nullable();
                    $table->integer('student_id')->unsigned();
                    $table->timestamps();

                    $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
                });
            }

            if (!Schema::connection('tenant')->hasTable('invoiceables')) {
                Schema::connection('tenant')->create('invoiceables', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('uid');
                    $table->float('amount');
                    $table->string('title', 250);
                    $table->longText('properties')->nullable();
                    $table->integer('invoice_id')->unsigned()->index();
                    $table->integer('student_id')->unsigned()->index();
                    $table->integer('quantity');
                    $table->morphs('invoiceable');
                    $table->timestamps();

                    $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
                    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                });
            }

            $studentsTable = Schema::connection('tenant')->getColumnListing('students');

            if (!in_array('address', $studentsTable)) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->text('address')->nullable();
                });
            }
            if (!in_array('city', $studentsTable)) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->string('city', 50)->nullable();
                });
            }

            if (!in_array('country', $studentsTable)) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->string('country', 50)->nullable();
                });
            }

            if (!in_array('postal_code', $studentsTable)) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->string('postal_code', 50)->nullable();
                });
            }

            if (!in_array('phone', $studentsTable)) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->string('phone', 250)->nullable();
                });
            }

            $invoicesTable = Schema::connection('tenant')->getColumnListing('invoices');

            if (!in_array('due_date', $invoicesTable)) {
                Schema::connection('tenant')->table('invoices', function (Blueprint $table) {
                    $table->date('due_date')->default('2020-01-01');
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 4) {
            $submissionStatusTable = Schema::connection('tenant')->getColumnListing('submission_statuses');

            if (!in_array('step', $submissionStatusTable)) {
                Schema::connection('tenant')->table('submission_statuses', function (Blueprint $table) {
                    $table->integer('step')->nullable();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 5) {
            if (!Schema::connection('tenant')->hasTable('schedules')) {
                Schema::connection('tenant')->create('schedules', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('label', 100);
                    $table->time('start_time');
                    $table->time('end_time');
                    $table->timestamps();
                });
            }
            if (!Schema::connection('tenant')->hasColumn('assistants', 'properties')) {
                Schema::connection('tenant')->table('assistants', function (Blueprint $table) {
                    $table->text('properties')->nullable();
                });
            }

            Schema::connection('tenant')->table('classroom_slots', function (Blueprint $table) {
                $table->integer('schedule_id')->unsigned()->nullable()->index();
                $table->time('start_time')->nullable()->change();
                $table->time('end_time')->nullable()->change();

                //$table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            });

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 6) {
            $pluginsTable = Schema::connection('tenant')->getColumnListing('plugins');

            if (!in_array('type', $pluginsTable)) {
                Schema::connection('tenant')->table('plugins', function (Blueprint $table) {
                    $table->string('type', 20)->nullable();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 7) {
            Schema::connection('tenant')->table('submissions', function (Blueprint $table) {
                $table->softDeletes();
            });

            Schema::connection('tenant')->table('students', function (Blueprint $table) {
                $table->string('avatar', 500)->nullable();
            });

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 8) {
            $submissionsTable = Schema::connection('tenant')->getColumnListing('submissions');

            if (!in_array('fields_progress_status', $submissionsTable)) {
                Schema::connection('tenant')->table('submissions', function (Blueprint $table) {
                    $table->integer('fields_progress_status')->nullable();
                    $table->integer('steps_progress_status')->nullable();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 9) {
            $agentsTable = Schema::connection('tenant')->getColumnListing('agents');

            if (!in_array('roles', $agentsTable)) {
                Schema::connection('tenant')->table('agents', function (Blueprint $table) {
                    $table->enum('roles', ['Super Admin', 'Agency Admin', 'Regular Agent'])->default('Regular Agent');
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 10) {
            if (!Schema::connection('tenant')->hasTable('customfields')) {
                // Add New Migrations here
                Schema::connection('tenant')->create('customfields', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->string('slug');
                    $table->string('field_type')->nullable();
                    $table->string('properties', 250);
                    $table->string('data', 250);
                    $table->timestamps();
                });
            }
            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 11) {
            $submissionsTable = Schema::connection('tenant')->getColumnListing('submissions');

            if (!in_array('uuid', $submissionsTable)) {
                Schema::connection('tenant')->table('submissions', function (Blueprint $table) {
                    $table->string('uuid', 20)->nullable();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 12) {
            if (Schema::connection('tenant')->hasTable('submissions')) {
                Schema::connection('tenant')->table('submissions', function (Blueprint $table) {
                    $table->longText('properties')->nullable()->change();
                });
            }
            if (!Schema::connection('tenant')->hasTable('contracts')) {
                Schema::connection('tenant')->create('contracts', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('uid')->unique();
                    $table->integer('student_id')->unsigned();
                    $table->integer('user_id')->unsigned()->nullable();
                    $table->integer('submission_id')->unsigned()->nullable();
                    $table->string('status');
                    $table->string('service');
                    $table->text('url')->nullable();
                    $table->longText('properties')->nullable();
                    $table->timestamps();
                });
            }
            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 13) {
            //            if (!Schema::connection('tenant')->hasTable('group_semester')) {
            //                Schema::create('group_semester', function (Blueprint $table) {
            //                    $table->increments('id');
            //                    $table->integer('group_id')->unsigned()->index();
            //                    $table->integer('semester_id')->unsigned()->index();
            //                });
            //            }
            //            $connection->version ++;
            //            $connection->save();
        }

        if ($connection->version <= 14) {
            $lessonTable = Schema::connection('tenant')->getColumnListing('lessons');

            if (!in_array('lessoneable_id', $lessonTable)) {
                Schema::connection('tenant')->table('lessons', function (Blueprint $table) {
                    $table->unsignedBigInteger('lessoneable_id')->nullable();
                    $table->string('lessoneable_type')->nullable();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 15) {
            $lessonTable = Schema::connection('tenant')->getColumnListing('lessons');

            if (!in_array('program_id', $lessonTable)) {
                Schema::connection('tenant')->table('lessons', function (Blueprint $table) {
                    $table->unsignedBigInteger('program_id')->nullable()->index();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 16) {
            if (!Schema::connection('tenant')->hasTable('envelopes')) {
                Schema::connection('tenant')->create('envelopes', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('title');
                    $table->string('service');
                    $table->longText('properties')->nullable();
                    $table->timestamps();
                });
            }

            $contractsTable = Schema::connection('tenant')->getColumnListing('contracts');

            if (!in_array('envelope_id', $contractsTable)) {
                Schema::connection('tenant')->table('contracts', function (Blueprint $table) {
                    $table->unsignedBigInteger('envelope_id')->nullable()->unsigned();
                    $table->string('title');
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 17) {
            if (!Schema::connection('tenant')->hasTable('semestereables')) {
                Schema::connection('tenant')->create('semestereables', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('semester_id')->unsigned()->index();
                    $table->morphs('semestereable');
                    $table->timestamps();
                });
            }

            $table = Schema::connection('tenant')->getColumnListing('groups');

            if (!in_array('schedule', $table)) {
                Schema::connection('tenant')->table('groups', function (Blueprint $table) {
                    $table->enum('schedule', ['morning', 'afternoon', 'both'])->default('both');
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 18) {
            if (!Schema::connection('tenant')->hasTable('lessoneables')) {
                Schema::connection('tenant')->create('lessoneables', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('lesson_id')->unsigned()->index();
                    $table->morphs('lessoneable');
                    $table->timestamps();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 19) {
            if (!Schema::connection('tenant')->hasTable('application_statuses')) {
                Schema::connection('tenant')->create('application_statuses', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('title');
                    $table->string('label')->nullable();
                    $table->timestamps();
                });
            }

            $connection->version++;
            $connection->save();
        }


        if ($connection->version <= 20) {
            $settings = Setting::byGroup("integrations");

            if (isset($settings['integrations']['integration_mautic'])) {
                $plugin = Plugin::updateOrCreate([
                    'name' => 'mautic'
                ], [
                    'published' => true,
                    'type'     =>   'crm',
                    'properties' => [
                        'is_default' => true,
                        'username' => $settings['integrations']['mautic_username'],
                        'password' => Crypt::encrypt($settings['integrations']['mautic_password']),
                        'base_url'  => $settings['integrations']['mautic_url']
                    ]
                ]);
            }
        }

        if ($connection->version < 21) {
            if (Schema::connection('tenant')->hasTable('invoices') && !Schema::connection('tenant')->hasColumn('invoices', 'submission_id')) {
                Schema::connection('tenant')->table('invoices', function (Blueprint $table) {
                    $table->integer('submission_id')->unsigned()->index()->nullable();
                });
            }
            $connection->version++;
            $connection->save();
        }


        if ($connection->version <= 22) {
            if (!Schema::connection('tenant')->hasTable('user_campuse')) {
                Schema::connection('tenant')->create('user_campuse', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('school_id')->unsigned()->index()->nullable();
                    $table->integer('user_id')->unsigned()->index()->nullable();
                    $table->integer('campus_id')->unsigned()->index()->nullable();
                    $table->timestamps();

                    /* $table->foreign('school_id')->references('id')->on('schools');
                    $table->foreign('user_id')->references('id')->on('users'); */
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 23) {

            if (!Schema::connection('tenant')->hasTable('campus_application')) {
                Schema::connection('tenant')->create('campus_application', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('campus_id')->index();
                    $table->unsignedBigInteger('application_id')->index();
                    $table->timestamps();
                });
            }

            if (!Schema::connection('tenant')->hasTable('campus_student')) {
                Schema::connection('tenant')->create('campus_student', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('campus_id')->index();
                    $table->unsignedBigInteger('student_id')->index();
                    $table->timestamps();
                });
            }



            /* $school = $event->tenant;
        // Find School Users and Roles to
        $users = $school->users()->with('roles')->get();

        foreach($users as $user)
        {
            foreach($user->roles as $role)
            {
                if($role->school_id != $school->id)
                {
                    // Check if Role is Exist
                    $newRole = Role::where([
                         'name'          => 'Super Admin',
                        'guard_name'    => 'web',
                        'school_id'     => $school->id,
                    ])->first();

                    if(!$newRole){
                        // Create Super Admin Role for the user in the school
                        $data = [
                            'name'          => 'Super Admin',
                            'guard_name'    => 'web',
                            'school_id'     => $school->id,
                            'full_access'   => true
                        ];
                        $newRole = Role::create($data);
                    }
                    $user->assignRole($newRole);
                }
            }
        } */
            $connection->version++;
            $connection->save();
        }


        if ($connection->version <= 24) {

            if (!Schema::connection('tenant')->hasTable('campus_envelope')) {
                Schema::connection('tenant')->create('campus_envelope', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('campus_id')->index();
                    $table->unsignedBigInteger('envelope_id')->index();
                    $table->timestamps();
                });
            }
            $connection->version++;
            $connection->save();
        }
        if ($connection->version <= 25) {

            if (!Schema::connection('tenant')->hasTable('group_schedule')) {
                Schema::connection('tenant')->create('group_schedule', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('group_id')->index();
                    $table->unsignedBigInteger('schedule_id')->index();
                    $table->timestamps();
                });
            }
            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 26) {

            if (!Schema::connection('tenant')->hasTable('educational_services')) {
                Schema::connection('tenant')->create('educational_services', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->string('code');
                    $table->text('description');
                    $table->float('amount');
                    $table->integer('educational_service_categories_id')->unsigned();
                    $table->timestamps();
                });
            }

            if (!Schema::connection('tenant')->hasTable('educational_service_categories')) {
                Schema::connection('tenant')->create('educational_service_categories', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->string('object')->default('educationl_service');
                    $table->string('color')->nullable();
                    $table->boolean('is_published')->default(true);
                    $table->timestamps();
                });
            }

            if (!Schema::connection('tenant')->hasColumn('courses', 'status')) {
                Schema::connection('tenant')->table('courses', function (Blueprint $table) {
                    $table->string('status')->nullable()->default('Scheduled');
                });
            }

            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 27) {

            if (!Schema::connection('tenant')->hasColumn('students', 'params')) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->longText('params')->nullable();
                });
            }
            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 28) {

            if (!Schema::connection('tenant')->hasColumn('customfields', 'for_forms')) {
                Schema::connection('tenant')->table('customfields', function (Blueprint $table) {
                    $table->boolean('for_forms')->default(false);
                });
            }
            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 29) {
            if (!Schema::connection('tenant')->hasTable('messages')) {
                Schema::connection('tenant')->create('messages', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('parent_id')->nullable();
                    $table->numericMorphs('sender');
                    $table->string('subject')->nullable();
                    $table->longText('body');
                    $table->softDeletes();
                    $table->timestamps();
                });
            }

            if (!Schema::connection('tenant')->hasTable('attachments')) {
                Schema::connection('tenant')->create('attachments', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->numericMorphs('object');
                    $table->string('url');
                    $table->dateTime('id_downloaded')->nullable();
                    $table->dateTime('id_viewed')->nullable();
                    $table->softDeletes();
                    $table->timestamps();
                });
            }
            if (!Schema::connection('tenant')->hasTable('recipients')) {
                Schema::connection('tenant')->create('recipients', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('message_id');
                    $table->dateTime('is_read')->nullable();
                    $table->numericMorphs('recipient');
                    $table->timestamps();
                });
            }
            $connection->version++;
            $connection->save();
        }


        if ($connection->version <= 30) {

            if (Schema::connection('tenant')->hasTable('attachments')) {

                if (!Schema::connection('tenant')->hasColumn('attachments', 'name')) {
                    Schema::connection('tenant')->table('attachments', function (Blueprint $table) {
                        $table->string('name');
                    });
                }

                if (Schema::connection('tenant')->hasColumn('attachments', 'attachment_type')) {
                    Schema::connection('tenant')->table('attachments', function (Blueprint $table) {
                        $table->renameColumn('attachment_type', 'object_type');
                    });
                }

                if (Schema::connection('tenant')->hasColumn('attachments', 'attachment_id')) {
                    Schema::connection('tenant')->table('attachments', function (Blueprint $table) {
                        $table->renameColumn('attachment_id', 'object_id');
                    });
                }
            }
            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 31) {
            if (Schema::connection('tenant')->hasTable('students')) {
                if (!Schema::connection('tenant')->hasColumn('students', 'owner_id')) {
                    $columns = Schema::connection('tenant')->getColumnListing('students');
                    $after = $columns[array_search('agent_id', $columns) - 1];
                    Schema::connection('tenant')->table('students', function (Blueprint $table) use ($after) {
                        $table->integer('owner_id')->after($after)->nullable();
                    });
                }
            }
            $connection->version++;
            $connection->save();
        }

        if ($connection->version <= 32) {

            $studentsTable = Schema::connection('tenant')->getColumnListing('students');

            if (!in_array('params', $studentsTable)) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->longText('params')->nullable();
                });
            }
            if (!in_array('properties', $studentsTable)) {
                Schema::connection('tenant')->table('students', function (Blueprint $table) {
                    $table->longText('properties')->nullable();
                });
            }


            if (!Schema::connection('tenant')->hasTable('document_builders')) {
                Schema::connection('tenant')->create('document_builders', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('selector');
                    $table->text('fields');
                    $table->text('properties');
                    $table->text('document');
                    $table->text('description')->nullable();
                    $table->timestamps();
                });
            }
            if (!Schema::connection('tenant')->hasTable('shareables')) {
                Schema::connection('tenant')->create('shareables', function (Blueprint $table) {
                    $table->id();
                    $table->morphs('shareable');
                    $table->unsignedBigInteger('documentable_id');
                    $table->string('documentable_type');
                    $table->boolean('is_active');
                    $table->json('properties');
                    $table->timestamps();
                });
            }

            $connection->version++;
            $connection->save();
        }

        if (!Schema::hasColumn('users', 'signature')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('signature')->nullable();
            });
        }
        
    }
}
