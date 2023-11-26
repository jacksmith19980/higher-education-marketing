<?php

namespace Database\Seeders\Tenant\School;

use App\Tenant\Models\Field;
use App\Tenant\Models\Section;
use Illuminate\Database\Seeder;
use App\Tenant\Models\Application;
use App\Tenant\Models\CustomField;
use Database\Seeders\Tenant\Application\SectionsSeeder;

class CustomFieldsSeeder extends Seeder
{
    protected $customFields = [
        'first_name' => [
                'name'              => 'First Name',
                'slug'              => 'first_name',
                'field_type'        => 'text',
                'object'            => 'students',
                'data'              => [],
                'section'           => 'personal',
                'is_published'      => true,
                'is_required'       => true,
                'for_applications'  => true,
                'enable_for'       => null,
                'properties'        => [
                    'unique'            => false,
                    'default_value'     => null,
                    'used_internally'   => true,
                    'trackable'         => false,
                ]
        ],
        'last_name' => [
                'name'              => 'Last Name',
                'slug'              => 'last_name',
                'field_type'        => 'text',
                'object'            => 'students',
                'data'              => [],
                'section'           => 'personal',
                'is_published'      => true,
                'is_required'       => true,
                'for_applications'  => true,
                'enable_for'       => null,
                'properties'        => [
                    'unique'            => false,
                    'default_value'     => null,
                    'used_internally'   => true,
                    'trackable'         => false,
                ]
        ],
        'email' => [
                'name'              => 'Email',
                'slug'              => 'email',
                'field_type'        => 'email',
                'object'            => 'students',
                'data'              => [],
                'section'           => 'personal',
                'is_published'      => true,
                'is_required'       => true,
                'for_applications'  => true,
                'enable_for'       => null,
                'properties'        => [
                    'unique'            => true,
                    'default_value'     => null,
                    'used_internally'   => true,
                    'trackable'         => false,
                ]
        ],
        'phone' => [
            'name'              => 'Phone',
            'slug'              => 'phone',
            'field_type'        => 'phone',
            'object'            => 'students',
            'data'              => [],
            'section'           => 'personal',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => true,
                'trackable'         => false,
            ]
        ],
        'stage' => [
                'name'              => 'Contact Type',
                'slug'              => 'stage',
                'field_type'        => 'list',
                'object'            => 'students',
                'data'               => [
                    "Applicant"     => "Applicant",
                    "Student"       => "Student",
                    "Lead"          => "Lead",
                ],
                /* 'data'              => [
                    [
                        'label'              => 'Applicant',
                        'value'              => 'Applicant'
                    ],
                    [
                        'label'              => 'Student',
                        'value'              => 'Student'
                    ],
                    [
                        'label'              => 'Lead',
                        'value'              => 'Lead'
                    ],
                ], */
                'section'           => 'personal',
                'is_published'      => true,
                'is_required'       => false,
                'for_applications'  => false,
                'enable_for'       => null,
                'properties'        => [
                    'unique'            => false,
                    'default_value'     => null,
                    'used_internally'   => true,
                    'trackable'         => true,
                ]
        ],
        /* 'program' => [
                'name'              => 'Program',
                'slug'              => 'program',
                'field_type'        => 'multi-list',
                'object'            => 'students',
                'data'              => [],
                'section'           => 'personal',
                'is_published'      => true,
                'is_required'       => true,
                'for_applications'  => true,
                'enable_for'       => null,
                'properties'        => [
                    'unique'            => false,
                    'default_value'     => null,
                    'used_internally'   => true,
                    'trackable'         => true,
                ]
        ], */
        'address' => [
                'name'              => 'Address',
                'slug'              => 'address',
                'field_type'        => 'textarea',
                'object'            => 'students',
                'data'              => [],
                'section'           => 'personal',
                'is_published'      => true,
                'is_required'       => false,
                'for_applications'  => true,
                'enable_for'       => null,
                'properties'        => [
                    'unique'            => false,
                    'default_value'     => null,
                    'used_internally'   => false,
                    'trackable'         => false,
                ]
        ],
        'city' => [
            'name'              => 'City',
            'slug'              => 'city',
            'field_type'        => 'text',
            'object'            => 'students',
            'data'              => [],
            'section'           => 'personal',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'country' => [
            'name'              => 'Country',
            'slug'              => 'country',
            'field_type'        => 'list',
            'object'            => 'students',
            'data'              => [

                'USA'           => 'USA',
                'Canada'        => 'Canada',

            ],
            'section'           => 'personal',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'postal_code' => [
            'name'              => 'Postal Code',
            'slug'              => 'postal_code',
            'field_type'        => 'text',
            'object'            => 'students',
            'data'              => [],
            'section'           => 'personal',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'agency_name' => [
                'name'              => 'Agency Name',
                'slug'              => 'name',
                'field_type'        => 'text',
                'object'            => 'agencies',
                'data'              => [],
                'section'           => 'general',
                'is_published'      => true,
                'is_required'       => true,
                'for_applications'  => true,
                'enable_for'       => null,
                'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => true,
                'trackable'         => false,
                ]
        ],
        'agency_email' => [
                'name'              => 'Agency Email',
                'slug'              => 'email',
                'field_type'        => 'email',
                'object'            => 'agencies',
                'data'              => [],
                'section'           => 'general',
                'is_published'      => true,
                'is_required'       => true,
                'for_applications'  => true,
                'enable_for'       => null,
                'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => true,
                'trackable'         => false,
                ]
        ],
        'agency_phone' => [
            'name'              => 'Agency Phone',
            'slug'              => 'phone',
            'field_type'        => 'phone',
            'object'            => 'agencies',
            'data'              => [],
            'section'           => 'general',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'agency_address' => [
            'name'              => 'Agency Address',
            'slug'              => 'address',
            'field_type'        => 'textarea',
            'object'            => 'agencies',
            'data'              => [],
            'section'           => 'general',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'agency_city' => [
            'name'              => 'Agency City',
            'slug'              => 'city',
            'field_type'        => 'text',
            'object'            => 'agencies',
            'data'              => [],
            'section'           => 'general',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'agency_country' => [
            'name'              => 'Agency Country',
            'slug'              => 'country',
            'field_type'        => 'text',
            'object'            => 'agencies',
            'data'              => [],
            'section'           => 'general',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'agency_postal_code' => [
            'name'              => 'Agency Postal Code',
            'slug'              => 'postal_code',
            'field_type'        => 'text',
            'object'            => 'agencies',
            'data'              => [],
            'section'           => 'general',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
        'agency_description' => [
            'name'              => 'Agency Description',
            'slug'              => 'description',
            'field_type'        => 'textarea',
            'object'            => 'agencies',
            'data'              => [],
            'section'           => 'general',
            'is_published'      => true,
            'is_required'       => false,
            'for_applications'  => true,
            'enable_for'       => null,
            'properties'        => [
                'unique'            => false,
                'default_value'     => null,
                'used_internally'   => false,
                'trackable'         => false,
            ]
        ],
    ];



    public function __construct()
    {

    }
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        foreach ($this->customFields as $customField) {
            CustomField::firstOrCreate([
                'slug'      => $customField['slug'],
                'object'    => $customField['object']
            ] , $customField);
        }
    }
}
