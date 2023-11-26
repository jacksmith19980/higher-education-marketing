<?php

namespace Database\Seeders\Tenant\Application;

use App\Tenant\Models\Application;
use App\Tenant\Models\Field;
use App\Tenant\Models\Section;
use Database\Seeders\Tenant\Application\SectionsSeeder;
use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    protected $application;
    protected $sections_order = [];
    protected $fields_order = [];

    protected $personal_information = [

          'first_name ' => [
                'label'         => 'First Name',

                'name'          => 'first_name',
                'field_type'    => 'field',
                'data'          => [],
                'properties' => [
                              'map'           => 'student|first_name',
                              'type'            => 'text',
                              'default'         => '',
                              'placeholder'     => 'First Name',
                              'helper'          => '',
                              'class'           => '',
                              'attr'            => '',
                              'show'            => true,
                              'listName'       => '',
                              'wrapper'         => [
                                      'columns' => '6',
                                      'class'   => '',
                                      'attr'    => '',
                                    ],
                              'label' => [
                                      'show'    => false,
                                      'text'    => '',
                                      'class'   => '',
                                      'attr'    => '',
                                    ],
                              'validation' => [

                                      'required' => 'This is required',

                                    ],

                              'smart'   => '',

                              'logic'   => [],

                              'delete'  => false,
                            ],
                        ],

            'last_name' => [
                'label'         => 'Last Name',

                'name'          => 'last_name',
                'field_type'    => 'field',
                'data'          => [],
                'properties' => [
                              'type'            => 'text',
                              'default'         => '',
                              'map'           => 'student|last_name',
                              'placeholder'     => 'Last Name',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required' => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => false,

                            ],

                        ],

            'email' => [

                'label'         => 'Email',

                'name'          => 'email',

                'field_type'    => 'field',

                'data'          => [],

                'properties' => [

                              'type'            => 'email',
                              'map'           => 'student|email',
                              'default'         => '',

                              'placeholder'     => 'Email',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required' => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => false,

                            ],

                        ],

            'date_of_birth' => [

                'label'         => 'Date of birth',

                'name'          => 'date_of_birth',

                'field_type'    => 'field',

                'data'          => [],

                'properties' => [

                              'type'            => 'date',

                              'default'         => '',

                              'placeholder'     => 'Date of birth',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required'      => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => true,

                            ],

                        ],

            'nationality' => [

                'label'         => 'Nationality',

                'name'          => 'nationality',

                'field_type'    => 'field',

                'data'          => [
                  'Canada'  => 'Canada',
                  'USA'     => 'USA',
                ],

                'properties' => [

                              'type'            => 'list',

                              'default'         => '',

                              'placeholder'     => 'Nationality',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required'      => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => true,

                            ],

                        ],

                'phone' => [

                    'label'         => 'Phone Number',

                    'name'          => 'phone',

                    'field_type'    => 'field',

                    'data'          => [],

                    'properties' => [

                                  'type'            => 'phone',

                                  'default'         => '',

                                  'placeholder'     => 'Phone Number',

                                  'helper'          => '',

                                  'class'           => '',

                                  'attr'            => '',

                                  'show'            => true,

                                  'listName'       => '',

                                  'wrapper'         => [

                                          'columns' => '6',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'label' => [

                                          'show'    => false,

                                          'text'    => '',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'validation' => [

                                          'required'      => 'This is required',

                                        ],

                                  'smart' => '',

                                  'logic' => [],

                                  'delete'  => true,

                                ],
                        ],

              'home_address' => [

                    'label'         => 'Home Address',

                    'name'          => 'home_address',

                    'field_type'    => 'field',

                    'data'          => [],

                    'properties' => [

                                  'type'            => 'textarea',

                                  'default'         => '',

                                  'placeholder'     => 'Home Address',

                                  'helper'          => '',

                                  'class'           => '',

                                  'attr'            => '',

                                  'show'            => true,

                                  'listName'       => '',

                                  'wrapper'         => [

                                          'columns' => '12',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'label' => [

                                          'show'    => false,

                                          'text'    => '',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'validation' => [

                                          'required'      => 'This is required',

                                        ],

                                  'smart' => '',

                                  'logic' => [],

                                  'delete'  => true,

                                ],

                        ],

                'zip_code' => [

                    'label'         => 'Zip Code',

                    'name'          => 'zip_code',

                    'field_type'    => 'field',

                    'data'          => [],

                    'properties' => [

                                  'type'            => 'text',

                                  'default'         => '',

                                  'placeholder'     => 'Zip code',

                                  'helper'          => '',

                                  'class'           => '',

                                  'attr'            => '',

                                  'show'            => true,

                                  'listName'       => '',

                                  'wrapper'         => [

                                          'columns' => '6',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'label' => [

                                          'show'    => false,

                                          'text'    => '',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'validation' => [

                                          'required'      => 'This is required',

                                        ],

                                  'smart' => '',

                                  'logic' => [],

                                  'delete'  => true,

                                ],

                        ],

                'state' => [

                    'label'         => 'State/Province',

                    'name'          => 'state',

                    'field_type'    => 'field',

                    'data'          => [],

                    'properties' => [

                                  'type'            => 'text',

                                  'default'         => '',

                                  'placeholder'     => 'State/Province',

                                  'helper'          => '',

                                  'class'           => '',

                                  'attr'            => '',

                                  'show'            => true,

                                  'listName'       => '',

                                  'wrapper'         => [

                                          'columns' => '6',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'label' => [

                                          'show'    => false,

                                          'text'    => '',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'validation' => [

                                          'required'      => 'This is required',

                                        ],

                                  'smart' => '',

                                  'logic' => [],

                                  'delete'  => true,

                                ],

                        ],

                'city' => [

                    'label'         => 'City',

                    'name'          => 'city',

                    'field_type'    => 'field',

                    'data'          => [],

                    'properties' => [

                                  'type'            => 'text',

                                  'default'         => '',

                                  'placeholder'     => 'City',

                                  'helper'          => '',

                                  'class'           => '',

                                  'attr'            => '',

                                  'show'            => true,

                                  'listName'       => '',

                                  'wrapper'         => [

                                          'columns' => '6',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'label' => [

                                          'show'    => false,

                                          'text'    => '',

                                          'class'   => '',

                                          'attr'    => '',

                                        ],

                                  'validation' => [

                                          'required'      => 'This is required',

                                        ],

                                  'smart' => '',

                                  'logic' => [],

                                  'delete'  => true,

                                ],

                        ],

                'country' => [

                        'label'         => 'Country',

                        'name'          => 'country',

                        'field_type'    => 'field',

                        'data'          => [

                            'Canada'  => 'Canada',
                            'USA'     => 'USA',

                          ],

                        'properties' => [

                              'type'            => 'list',

                              'default'         => '',

                              'placeholder'     => 'Country',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required'      => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

        'delete'  => true,

                            ],

                        ],

                  'how_did_you_hear_about_us' => [

                        'label'         => 'How did you hear about us?',

                        'name'          => 'how_did_you_hear_about_us',

                        'field_type'    => 'field',

                        'data'          => [

                              'Social media'      => 'Social media',

                              'Referral'          => 'Referral',

                              'Google'            => 'Google',

                              'Bing'              => 'Bing',

                              'Email'             => 'Email',

                              'Fair'              => 'Fair',

                              'Facebook'          => 'Facebook',

                              'Educational Portal'=> 'Educational Portal',

                              'Educational Agent' => 'Educational Agent',

                              'Chinese website'   => 'Chinese website',

                                        ],

                        'properties' => [

                              'type'            => 'list',

                              'default'         => '',

                              'placeholder'     => 'How did you hear about us?',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => 'custom_list',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required'      => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => true,

                            ],

                        ],

                  'how_did_you_hear_about_us_details' => [

                        'label'         => 'Details',

                        'name'          => 'how_did_you_hear_about_us_details',

                        'field_type'    => 'field',

                        'data'          => [],

                        'properties' => [

                              'type'            => 'text',

                              'default'         => '',

                              'placeholder'     => 'Details',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                  'required'  => false,

                              ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => true,

                            ],

                        ],

                      'why_us' => [

                        'label'         => 'Why Us?',

                        'name'          => 'why_us',

                        'field_type'    => 'field',

                        'data'          => [

                              'Swiss Education' => 'Swiss Education',

                              'Programs are in English' => 'Programs are in English',

                              'Private School' => 'Private School',

                              'Educational System' => 'Educational System',

                              'Campus Location' => 'Campus Location',

                              'International B-School' => 'International B-School',

                              'Other' => 'Other',

                              'It was a recommendation' => 'It was a recommendation',

                                        ],

                        'properties' => [

                              'type'            => 'list',

                              'default'         => '',

                              'placeholder'     => 'Why Us?',

                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => 'custom_list',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required'      => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => true,

                            ],

                        ],

  ];
    protected $program_details = [
        'program' => [
                'label'         => 'Program',
                'name'          => 'program',
                'field_type'    => 'field',
                'data'          => [],
                'properties' => [
                              'type'            => 'text',
                              'default'         => '',
                              'placeholder'     => 'Program',
                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required' => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => false,

                            ],

                        ],
          'start_date' => [
                'label'         => 'Desired Start Date',
                'name'          => 'start_date',
                'field_type'    => 'field',
                'data'          => [],
                'properties' => [
                              'type'            => 'date',
                              'startDate' => null,
                              'endDate' => null,
                              'startView' => 'days',
                              'format' => 'YYYY-MM-DD',
                              'default'         => '',
                              'placeholder'     => 'Desired Start Date',
                              'helper'          => '',

                              'class'           => '',

                              'attr'            => '',

                              'show'            => true,

                              'listName'       => '',

                              'wrapper'         => [

                                      'columns' => '6',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'label' => [

                                      'show'    => false,

                                      'text'    => '',

                                      'class'   => '',

                                      'attr'    => '',

                                    ],

                              'validation' => [

                                      'required' => 'This is required',

                                    ],

                              'smart' => '',

                              'logic' => [],

                              'delete'  => false,

                            ],

                        ],

    ];

    protected $agency_information = [
      'agency_name ' => [
        'label' => 'Agency Name',
        'name'          => 'agency',
        'field_type'    => 'field',
        'data'          => [],
        'properties' => [
              'type'            => 'text',
              'default'         => '',
              'placeholder'     => 'Agency Name',
              'helper'          => '',
              'class'           => '',
              'attr'            => '',
              'show'            => true,
              'listName'       => '',
              'wrapper'         => [
                      'columns' => '6',
                      'class'   => '',
                      'attr'    => '',
                    ],
              'label' => [
                      'show'    => false,
                      'text'    => '',
                      'class'   => '',
                      'attr'    => '',
                    ],
              'validation' => [
                      'required' => 'This is required',
                    ],
              'smart'   => '',
              'logic'   => [],
              'delete'  => false,
            ],
        ],
        'agency_email' => [
          'label'         => 'Agency Email',
          'name'          => 'agency_email',
          'field_type'    => 'field',
          'data'          => [],
          'properties' => [
                        'type'            => 'email',
                        'default'         => '',
                        'placeholder'     => 'Agency Email',
                        'helper'          => '',
                        'class'           => '',
                        'attr'            => '',
                        'show'            => true,
                        'listName'       => '',
                        'wrapper'         => [
                                'columns' => '6',
                                'class'   => '',
                                'attr'    => '',
                              ],
                        'label' => [
                                'show'    => false,
                                'text'    => '',
                                'class'   => '',
                                'attr'    => '',
                              ],
                        'validation' => [
                                'required' => 'This is required',
                              ],
                        'smart' => '',
                        'logic' => [],
                        'delete'  => false,
                      ],
                  ],
    ];

    public function __construct()
    {
        //Get the Last Created Application
        $this->application = Application::orderBy('id', 'desc')->first();
    }

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        if ($this->application->object == 'student' || $this->application->object == 'form') {
            $this->createStudentApplicationSections();
        }

        if ($this->application->object == 'agency') {
            $this->createAgencyApplicationSections();
        }
    }

    protected function createAgencyApplicationSections()
    {
        // Create Agent Information Default Section
        $section = Section::create([
          'title'         => 'Agency Information',
          'properties'    => [
              'label'             => 'Agency information',
              'hidden'            => false,
              'icon'              => '',
          ],
      ]);

        foreach ($this->agency_information as $default_field) {
            $default_field['section_id'] = $section->id;
            $field = Field::create($default_field);
            array_push($this->fields_order, $field->id);
        }

        $section->fields_order = $this->fields_order;
        $section->save();
        $this->application->sections()->attach($section);

        array_push($this->sections_order, $section->id);
        $this->application->sections_order = $this->sections_order;
        $this->application->save();
    }

    protected function createStudentApplicationSections()
    {

      // Create Agent Information Default Section
        $section = Section::create([
        'title'         => 'Personal',
        'properties'    => [
                'label'             => 'Personal information',
                'hidden'            => false,
                'icon'              => '',
                ],
    ]);

        foreach ($this->personal_information as $default_field) {
            $default_field['section_id'] = $section->id;
            $field = Field::create($default_field);
            array_push($this->fields_order, $field->id);
        }

        $section->fields_order = $this->fields_order;
        $section->save();
        $this->application->sections()->attach($section);
        array_push($this->sections_order, $section->id);

        // Program Sections
        $section = Section::create([
        'title'         => 'Program',
        'properties'    => [
                'label'             => 'Program Details',
                'hidden'            => false,
                'icon'              => '',
                ],
    ]);
        $programFields = [];
        //Add Fields to Program Section
        foreach ($this->program_details as $default_field) {
            $default_field['section_id'] = $section->id;
            $field = Field::create($default_field);
            array_push($programFields, $field->id);
        }
        $section->fields_order = $programFields;
        $section->save();
        $this->application->sections()->attach($section);
        array_push($this->sections_order, $section->id);

        $this->application->sections_order = $this->sections_order;
        $this->application->save();
    }
}
