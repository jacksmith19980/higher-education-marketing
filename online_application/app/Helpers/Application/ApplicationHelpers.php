<?php

namespace App\Helpers\Application;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Tenant\Models\Addon;
use App\Tenant\Models\Field;
use App\Helpers\SchoolHelper;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Application;
use App\Tenant\Models\CustomField;

/**
 * Application Helper
 */
class ApplicationHelpers
{
    /**
     * Return a list of countries
     */
    public static function getCoutriesList()
    {
        return [
            'Abkhazia'                                     => 'Abkhazia',
            'Afghanistan'                                  => 'Afghanistan',
            'Aland'                                        => 'Aland',
            'Albania'                                      => 'Albania',
            'Algeria'                                      => 'Algeria',
            'American Samoa'                               => 'American Samoa',
            'Andorra'                                      => 'Andorra',
            'Angola'                                       => 'Angola',
            'Anguilla'                                     => 'Anguilla',
            'Antarctica'                                   => 'Antarctica',
            'Antigua and Barbuda'                          => 'Antigua and Barbuda',
            'Argentina'                                    => 'Argentina',
            'Armenia'                                      => 'Armenia',
            'Aruba'                                        => 'Aruba',
            'Australia'                                    => 'Australia',
            'Austria'                                      => 'Austria',
            'Azerbaijan'                                   => 'Azerbaijan',
            'Bahamas'                                      => 'Bahamas',
            'Bahrain'                                      => 'Bahrain',
            'Bangladesh'                                   => 'Bangladesh',
            'Barbados'                                     => 'Barbados',
            'Basque Country'                               => 'Basque Country',
            'Belarus'                                      => 'Belarus',
            'Belgium'                                      => 'Belgium',
            'Belize'                                       => 'Belize',
            'Benin'                                        => 'Benin',
            'Bermuda'                                      => 'Bermuda',
            'Bhutan'                                       => 'Bhutan',
            'Bolivia'                                      => 'Bolivia',
            'Bosnia and Herzegovina'                       => 'Bosnia and Herzegovina',
            'Botswana'                                     => 'Botswana',
            'Brazil'                                       => 'Brazil',
            'British Antarctic Territory'                  => 'British Antarctic Territory',
            'British Virgin Islands'                       => 'British Virgin Islands',
            'Brunei'                                       => 'Brunei',
            'Bulgaria'                                     => 'Bulgaria',
            'Burkina Faso'                                 => 'Burkina Faso',
            'Burundi'                                      => 'Burundi',
            'Cambodia'                                     => 'Cambodia',
            'Cameroon'                                     => 'Cameroon',
            'Canada'                                       => 'Canada',
            'Canary Islands'                               => 'Canary Islands',
            'Cape Verde'                                   => 'Cape Verde',
            'Cayman Islands'                               => 'Cayman Islands',
            'Central African Republic'                     => 'Central African Republic',
            'Chad'                                         => 'Chad',
            'Chile'                                        => 'Chile',
            'China'                                        => 'China',
            'Christmas Island'                             => 'Christmas Island',
            'Cocos Keeling Islands'                        => 'Cocos Keeling Islands',
            'Colombia'                                     => 'Colombia',
            'Commonwealth'                                 => 'Commonwealth',
            'Comoros'                                      => 'Comoros',
            'Cook Islands'                                 => 'Cook Islands',
            'Costa Rica'                                   => 'Costa Rica',
            'Cote dIvoire'                                 => 'Cote dIvoire',
            'Croatia'                                      => 'Croatia',
            'Cuba'                                         => 'Cuba',
            'Curacao'                                      => 'Curacao',
            'Cyprus'                                       => 'Cyprus',
            'Czech Republic'                               => 'Czech Republic',
            'Democratic Republic of the Congo'             => 'Democratic Republic of the Congo',
            'Denmark'                                      => 'Denmark',
            'Djibouti'                                     => 'Djibouti',
            'Dominica'                                     => 'Dominica',
            'Dominican Republic'                           => 'Dominican Republic',
            'East Timor'                                   => 'East Timor',
            'Ecuador'                                      => 'Ecuador',
            'Egypt'                                        => 'Egypt',
            'El Salvador'                                  => 'El Salvador',
            'England'                                      => 'England',
            'Equatorial Guinea'                            => 'Equatorial Guinea',
            'Eritrea'                                      => 'Eritrea',
            'Estonia'                                      => 'Estonia',
            'Ethiopia'                                     => 'Ethiopia',
            'European Union'                               => 'European Union',
            'Falkland Islands'                             => 'Falkland Islands',
            'Faroes'                                       => 'Faroes',
            'Fiji'                                         => 'Fiji',
            'Finland'                                      => 'Finland',
            'France'                                       => 'France',
            'French Polynesia'                             => 'French Polynesia',
            'French Southern Territories'                  => 'French Southern Territories',
            'Gabon'                                        => 'Gabon',
            'Gambia'                                       => 'Gambia',
            'Georgia'                                      => 'Georgia',
            'Germany'                                      => 'Germany',
            'Ghana'                                        => 'Ghana',
            'Gibraltar'                                    => 'Gibraltar',
            'Greece'                                       => 'Greece',
            'Greenland'                                    => 'Greenland',
            'Grenada'                                      => 'Grenada',
            'Guadeloupe'                                   => 'Guadeloupe',
            'Guam'                                         => 'Guam',
            'Guatemala'                                    => 'Guatemala',
            'Guernsey'                                     => 'Guernsey',
            'Guinea Bissau'                                => 'Guinea Bissau',
            'Guinea'                                       => 'Guinea',
            'Guyana'                                       => 'Guyana',
            'Haiti'                                        => 'Haiti',
            'Honduras'                                     => 'Honduras',
            'Hong Kong'                                    => 'Hong Kong',
            'Hungary'                                      => 'Hungary',
            'Iceland'                                      => 'Iceland',
            'India'                                        => 'India',
            'Indonesia'                                    => 'Indonesia',
            'Iran'                                         => 'Iran',
            'Iraq'                                         => 'Iraq',
            'Ireland'                                      => 'Ireland',
            'Isle of Man'                                  => 'Isle of Man',
            'Israel'                                       => 'Israel',
            'Italy'                                        => 'Italy',
            'Jamaica'                                      => 'Jamaica',
            'Japan'                                        => 'Japan',
            'Jersey'                                       => 'Jersey',
            'Jordan'                                       => 'Jordan',
            'Kazakhstan'                                   => 'Kazakhstan',
            'Kenya'                                        => 'Kenya',
            'Kiribati'                                     => 'Kiribati',
            'Kosovo'                                       => 'Kosovo',
            'Kuwait'                                       => 'Kuwait',
            'Kyrgyzstan'                                   => 'Kyrgyzstan',
            'Laos'                                         => 'Laos',
            'Latvia'                                       => 'Latvia',
            'Lebanon'                                      => 'Lebanon',
            'Lesotho'                                      => 'Lesotho',
            'Liberia'                                      => 'Liberia',
            'Libya'                                        => 'Libya',
            'Liechtenstein'                                => 'Liechtenstein',
            'Lithuania'                                    => 'Lithuania',
            'Luxembourg'                                   => 'Luxembourg',
            'Macau'                                        => 'Macau',
            'Macedonia'                                    => 'Macedonia',
            'Madagascar'                                   => 'Madagascar',
            'Malawi'                                       => 'Malawi',
            'Malaysia'                                     => 'Malaysia',
            'Maldives'                                     => 'Maldives',
            'Mali'                                         => 'Mali',
            'Malta'                                        => 'Malta',
            'Mars'                                         => 'Mars',
            'Marshall Islands'                             => 'Marshall Islands',
            'Martinique'                                   => 'Martinique',
            'Mauritania'                                   => 'Mauritania',
            'Mauritius'                                    => 'Mauritius',
            'Mayotte'                                      => 'Mayotte',
            'Mexico'                                       => 'Mexico',
            'Micronesia'                                   => 'Micronesia',
            'Moldova'                                      => 'Moldova',
            'Monaco'                                       => 'Monaco',
            'Mongolia'                                     => 'Mongolia',
            'Montenegro'                                   => 'Montenegro',
            'Montserrat'                                   => 'Montserrat',
            'Morocco'                                      => 'Morocco',
            'Mozambique'                                   => 'Mozambique',
            'Myanmar'                                      => 'Myanmar',
            'NATO'                                         => 'NATO',
            'Nagorno Karabakh'                             => 'Nagorno Karabakh',
            'Namibia'                                      => 'Namibia',
            'Nauru'                                        => 'Nauru',
            'Nepal'                                        => 'Nepal',
            'Netherlands Antilles'                         => 'Netherlands Antilles',
            'Netherlands'                                  => 'Netherlands',
            'New Caledonia'                                => 'New Caledonia',
            'New Zealand'                                  => 'New Zealand',
            'Nicaragua'                                    => 'Nicaragua',
            'Niger'                                        => 'Niger',
            'Nigeria'                                      => 'Nigeria',
            'Niue'                                         => 'Niue',
            'Norfolk Island'                               => 'Norfolk Island',
            'North Korea'                                  => 'North Korea',
            'Northern Cyprus'                              => 'Northern Cyprus',
            'Northern Mariana Islands'                     => 'Northern Mariana Islands',
            'Norway'                                       => 'Norway',
            'Olympics'                                     => 'Olympics',
            'Oman'                                         => 'Oman',
            'Pakistan'                                     => 'Pakistan',
            'Palau'                                        => 'Palau',
            'Palestine'                                    => 'Palestine',
            'Panama'                                       => 'Panama',
            'Papua New Guinea'                             => 'Papua New Guinea',
            'Paraguay'                                     => 'Paraguay',
            'Peru'                                         => 'Peru',
            'Philippines'                                  => 'Philippines',
            'Pitcairn Islands'                             => 'Pitcairn Islands',
            'Poland'                                       => 'Poland',
            'Portugal'                                     => 'Portugal',
            'Puerto Rico'                                  => 'Puerto Rico',
            'Qatar'                                        => 'Qatar',
            'Red Cross'                                    => 'Red Cross',
            'Republic of the Congo'                        => 'Republic of the Congo',
            'Romania'                                      => 'Romania',
            'Russia'                                       => 'Russia',
            'Rwanda'                                       => 'Rwanda',
            'Saint Barthelemy'                             => 'Saint Barthelemy',
            'Saint Helena'                                 => 'Saint Helena',
            'Saint Kitts and Nevis'                        => 'Saint Kitts and Nevis',
            'Saint Lucia'                                  => 'Saint Lucia',
            'Saint Martin'                                 => 'Saint Martin',
            'Saint Pierre and Miquelon'                    => 'Saint Pierre and Miquelon',
            'Saint Vincent and the Grenadines'             => 'Saint Vincent and the Grenadines',
            'Samoa'                                        => 'Samoa',
            'San Marino'                                   => 'San Marino',
            'Sao Tome and Principe'                        => 'Sao Tome and Principe',
            'Saudi Arabia'                                 => 'Saudi Arabia',
            'Scotland'                                     => 'Scotland',
            'Senegal'                                      => 'Senegal',
            'Serbia'                                       => 'Serbia',
            'Seychelles'                                   => 'Seychelles',
            'Sierra Leone'                                 => 'Sierra Leone',
            'Singapore'                                    => 'Singapore',
            'Slovakia'                                     => 'Slovakia',
            'Slovenia'                                     => 'Slovenia',
            'Solomon Islands'                              => 'Solomon Islands',
            'Somalia'                                      => 'Somalia',
            'Somaliland'                                   => 'Somaliland',
            'South Africa'                                 => 'South Africa',
            'South Georgia and the South Sandwich Islands' => 'South Georgia and the South Sandwich Islands',
            'South Korea'                                  => 'South Korea',
            'South Ossetia'                                => 'South Ossetia',
            'South Sudan'                                  => 'South Sudan',
            'Spain'                                        => 'Spain',
            'Sri Lanka'                                    => 'Sri Lanka',
            'Sudan'                                        => 'Sudan',
            'Suriname'                                     => 'Suriname',
            'Swaziland'                                    => 'Swaziland',
            'Sweden'                                       => 'Sweden',
            'Switzerland'                                  => 'Switzerland',
            'Syria'                                        => 'Syria',
            'Taiwan'                                       => 'Taiwan',
            'Tajikistan'                                   => 'Tajikistan',
            'Tanzania'                                     => 'Tanzania',
            'Thailand'                                     => 'Thailand',
            'Togo'                                         => 'Togo',
            'Tokelau'                                      => 'Tokelau',
            'Tonga'                                        => 'Tonga',
            'Trinidad and Tobago'                          => 'Trinidad and Tobago',
            'Tunisia'                                      => 'Tunisia',
            'Turkey'                                       => 'Turkey',
            'Turkmenistan'                                 => 'Turkmenistan',
            'Turks and Caicos Islands'                     => 'Turks and Caicos Islands',
            'Tuvalu'                                       => 'Tuvalu',
            'US Virgin Islands'                            => 'US Virgin Islands',
            'Uganda'                                       => 'Uganda',
            'Ukraine'                                      => 'Ukraine',
            'United Arab Emirates'                         => 'United Arab Emirates',
            'United Kingdom'                               => 'United Kingdom',
            'United Nations'                               => 'United Nations',
            'United States'                                => 'United States',
            'Unknown'                                      => 'Unknown',
            'Uruguay'                                      => 'Uruguay',
            'Uzbekistan'                                   => 'Uzbekistan',
            'Vanuatu'                                      => 'Vanuatu',
            'Vatican City'                                 => 'Vatican City',
            'Venezuela'                                    => 'Venezuela',
            'Vietnam'                                      => 'Vietnam',
            'Wales'                                        => 'Wales',
            'Wallis And Futuna'                            => 'Wallis And Futuna',
            'Western Sahara'                               => 'Western Sahara',
            'Yemen'                                        => 'Yemen',
            'Zambia'                                       => 'Zambia',
            'Zimbabwe'                                     => 'Zimbabwe',
        ];
    }

    public static $actions = [
        ''                        => 'Select Action',
        'redirect-to-url'         => 'Redirect to URL after the application is submitted',
        'send-notification-email' => 'Send Notification Email after the application is submitted',
        'send-thank-you-email'    => 'Send Thank You Email after the application is submitted',
    ];

    public static function getCountryCode()
    {
        return  [
            'ABW' =>	'Aruba',
            'AFG' =>	'Afghanistan',
            'AGO' =>	'Angola',
            'AIA' =>	'Anguilla',
            'ALA' =>	'Åland Islands',
            'ALB' =>	'Albania',
            'AND' =>	'Andorra',
            'ARE' =>	'United Arab Emirates',
            'ARG' =>	'Argentina',
            'ARM' =>	'Armenia',
            'ASM' =>	'American Samoa',
            'ATA' =>	'Antarctica',
            'ATF' =>	'French Southern Territories',
            'ATG' =>	'Antigua and Barbuda',
            'AUS' =>	'Australia',
            'AUT' =>	'Austria',
            'AZE' =>	'Azerbaijan',
            'BDI' =>	'Burundi',
            'BEL' =>	'Belgium',
            'BEN' =>	'Benin',
            'BES' =>	'Bonaire, Sint Eustatius and Saba',
            'BFA' =>	'Burkina Faso',
            'BGD' =>	'Bangladesh',
            'BGR' =>	'Bulgaria',
            'BHR' =>	'Bahrain',
            'BHS' =>	'Bahamas',
            'BIH' =>	'Bosnia and Herzegovina',
            'BLM' =>	'Saint Barthélemy',
            'BLR' =>	'Belarus',
            'BLZ' =>	'Belize',
            'BMU' =>	'Bermuda',
            'BOL' =>	'Bolivia, Plurinational State of',
            'BRA' =>	'Brazil',
            'BRB' =>	'Barbados',
            'BRN' =>	'Brunei Darussalam',
            'BTN' =>	'Bhutan',
            'BVT' =>	'Bouvet Island',
            'BWA' =>	'Botswana',
            'CAF' =>	'Central African Republic',
            'CAN' =>	'Canada',
            'CCK' =>	'Cocos (Keeling) Islands',
            'CHE' =>	'Switzerland',
            'CHL' =>	'Chile',
            'CHN' =>	'China',
            'CIV' =>	"Côte d'Ivoire",
            'CMR' =>	'Cameroon',
            'COD' =>	'Congo, the Democratic Republic of the',
            'COG' =>	'Congo',
            'COK' =>	'Cook Islands',
            'COL' =>	'Colombia',
            'COM' =>	'Comoros',
            'CPV' =>	'Cape Verde',
            'CRI' =>	'Costa Rica',
            'CUB' =>	'Cuba',
            'CUW' =>	'Curaçao',
            'CXR' =>	'Christmas Island',
            'CYM' =>	'Cayman Islands',
            'CYP' =>	'Cyprus',
            'CZE' =>	'Czech Republic',
            'DEU' =>	'Germany',
            'DJI' =>	'Djibouti',
            'DMA' =>	'Dominica',
            'DNK' =>	'Denmark',
            'DOM' =>	'Dominican Republic',
            'DZA' =>	'Algeria',
            'ECU' =>	'Ecuador',
            'EGY' =>	'Egypt',
            'ERI' =>	'Eritrea',
            'ESH' =>	'Western Sahara',
            'ESP' =>	'Spain',
            'EST' =>	'Estonia',
            'ETH' =>	'Ethiopia',
            'FIN' =>	'Finland',
            'FJI' =>	'Fiji',
            'FLK' =>	'Falkland Islands (Malvinas)',
            'fr'  =>	'France',
            'FRO' =>	'Faroe Islands',
            'FSM' =>	'Micronesia, Federated States of',
            'GAB' =>	'Gabon',
            'GBR' =>	'United Kingdom',
            'GEO' =>	'Georgia',
            'GGY' =>	'Guernsey',
            'GHA' =>	'Ghana',
            'GIB' =>	'Gibraltar',
            'GIN' =>	'Guinea',
            'GLP' =>	'Guadeloupe',
            'GMB' =>	'Gambia',
            'GNB' =>	'Guinea-Bissau',
            'GNQ' =>	'Equatorial Guinea',
            'GRC' =>	'Greece',
            'GRD' =>	'Grenada',
            'GRL' =>	'Greenland',
            'GTM' =>	'Guatemala',
            'GUF' =>	'French Guiana',
            'GUM' =>	'Guam',
            'GUY' =>	'Guyana',
            'HKG' =>	'Hong Kong',
            'HMD' =>	'Heard Island and McDonald Islands',
            'HND' =>	'Honduras',
            'HRV' =>	'Croatia',
            'HTI' =>	'Haiti',
            'HUN' =>	'Hungary',
            'IDN' =>	'Indonesia',
            'IMN' =>	'Isle of Man',
            'IND' =>	'India',
            'IOT' =>	'British Indian Ocean Territory',
            'IRL' =>	'Ireland',
            'IRN' =>	'Iran, Islamic Republic of',
            'IRQ' =>	'Iraq',
            'ISL' =>	'Iceland',
            'ISR' =>	'Israel',
            'ITA' =>	'Italy',
            'JAM' =>	'Jamaica',
            'JEY' =>	'Jersey',
            'JOR' =>	'Jordan',
            'JPN' =>	'Japan',
            'KAZ' =>	'Kazakhstan',
            'KEN' =>	'Kenya',
            'KGZ' =>	'Kyrgyzstan',
            'KHM' =>	'Cambodia',
            'KIR' =>	'Kiribati',
            'KNA' =>	'Saint Kitts and Nevis',
            'KOR' =>	'Korea, Republic of',
            'KWT' =>	'Kuwait',
            'LAO' =>	"Lao People's Democratic Republic",
            'LBN' =>	'Lebanon',
            'LBR' =>	'Liberia',
            'LBY' =>	'Libya',
            'LCA' =>	'Saint Lucia',
            'LIE' =>	'Liechtenstein',
            'LKA' =>	'Sri Lanka',
            'LSO' =>	'Lesotho',
            'LTU' =>	'Lithuania',
            'LUX' =>	'Luxembourg',
            'LVA' =>	'Latvia',
            'MAC' =>	'Macao',
            'MAF' =>	'Saint Martin (French part)',
            'MAR' =>	'Morocco',
            'MCO' =>	'Monaco',
            'MDA' =>	'Moldova, Republic of',
            'MDG' =>	'Madagascar',
            'MDV' =>	'Maldives',
            'MEX' =>	'Mexico',
            'MHL' =>	'Marshall Islands',
            'MKD' =>	'Macedonia, the former Yugoslav Republic of',
            'MLI' =>	'Mali',
            'MLT' =>	'Malta',
            'MMR' =>	'Myanmar',
            'MNE' =>	'Montenegro',
            'MNG' =>	'Mongolia',
            'MNP' =>	'Northern Mariana Islands',
            'MOZ' =>	'Mozambique',
            'MRT' =>	'Mauritania',
            'MSR' =>	'Montserrat',
            'MTQ' =>	'Martinique',
            'MUS' =>	'Mauritius',
            'MWI' =>	'Malawi',
            'MYS' =>	'Malaysia',
            'MYT' =>	'Mayotte',
            'NAM' =>	'Namibia',
            'NCL' =>	'New Caledonia',
            'NER' =>	'Niger',
            'NFK' =>	'Norfolk Island',
            'NGA' =>	'Nigeria',
            'NIC' =>	'Nicaragua',
            'NIU' =>	'Niue',
            'NLD' =>	'Netherlands',
            'NOR' =>	'Norway',
            'NPL' =>	'Nepal',
            'NRU' =>	'Nauru',
            'NZL' =>	'New Zealand',
            'OMN' =>	'Oman',
            'PAK' =>	'Pakistan',
            'PAN' =>	'Panama',
            'PCN' =>	'Pitcairn',
            'PER' =>	'Peru',
            'PHL' =>	'Philippines',
            'PLW' =>	'Palau',
            'PNG' =>	'Papua New Guinea',
            'POL' =>	'Poland',
            'PRI' =>	'Puerto Rico',
            'PRK' =>	"Korea, Democratic People's Republic of",
            'PRT' =>	'Portugal',
            'PRY' =>	'Paraguay',
            'PSE' =>	'Palestine, State of',
            'PYF' =>	'French Polynesia',
            'QAT' =>	'Qatar',
            'REU' =>	'Réunion',
            'ROU' =>	'Romania',
            'RUS' =>	'Russian Federation',
            'RWA' =>	'Rwanda',
            'SAU' =>	'Saudi Arabia',
            'SDN' =>	'Sudan',
            'SEN' =>	'Senegal',
            'SGP' =>	'Singapore',
            'SGS' =>	'South Georgia and the South Sandwich Islands',
            'SHN' =>	'Saint Helena, Ascension and Tristan da Cunha',
            'SJM' =>	'Svalbard and Jan Mayen',
            'SLB' =>	'Solomon Islands',
            'SLE' =>	'Sierra Leone',
            'SLV' =>	'El Salvador',
            'SMR' =>	'San Marino',
            'SOM' =>	'Somalia',
            'SPM' =>	'Saint Pierre and Miquelon',
            'SRB' =>	'Serbia',
            'SSD' =>	'South Sudan',
            'STP' =>	'Sao Tome and Principe',
            'SUR' =>	'Suriname',
            'SVK' =>	'Slovakia',
            'SVN' =>	'Slovenia',
            'SWE' =>	'Sweden',
            'SWZ' =>	'Swaziland',
            'SXM' =>	'Sint Maarten (Dutch part)',
            'SYC' =>	'Seychelles',
            'SYR' =>	'Syrian Arab Republic',
            'TCA' =>	'Turks and Caicos Islands',
            'TCD' =>	'Chad',
            'TGO' =>	'Togo',
            'THA' =>	'Thailand',
            'TJK' =>	'Tajikistan',
            'TKL' =>	'Tokelau',
            'TKM' =>	'Turkmenistan',
            'TLS' =>	'Timor-Leste',
            'TON' =>	'Tonga',
            'TTO' =>	'Trinidad and Tobago',
            'TUN' =>	'Tunisia',
            'TUR' =>	'Turkey',
            'TUV' =>	'Tuvalu',
            'TWN' =>	'Taiwan, Province of China',
            'TZA' =>	'Tanzania, United Republic of',
            'UGA' =>	'Uganda',
            'UKR' =>	'Ukraine',
            'UMI' =>	'United States Minor Outlying Islands',
            'URY' =>	'Uruguay',
            'USA' =>	'United States',
            'UZB' =>	'Uzbekistan',
            'VAT' =>	'Holy See (Vatican City State)',
            'VCT' =>	'Saint Vincent and the Grenadines',
            'VEN' =>	'Venezuela, Bolivarian Republic of',
            'VGB' =>	'Virgin Islands, British',
            'VIR' =>	'Virgin Islands, U.S.',
            'VNM' =>	'Viet Nam',
            'VUT' =>	'Vanuatu',
            'WLF' =>	'Wallis and Futuna',
            'WSM' =>	'Samoa',
            'YEM' =>	'Yemen',
            'ZAF' =>	'South Africa',
            'ZMB' =>	'Zambia',
            'ZWE' =>	'Zimbabwe',
        ];
    }

    public static $application_themes = [
        /* 'gbsg' => [
            'title' => 'Stepped Blue Application',
            'customization' => [
                'primary_color' => [
                    'title' => 'Primary Color',
                    'type' => 'color-input',
                    'default' => '#d00909'
                ],
                'secondary_color' => [
                    'title' => 'Secondary Color',
                    'type' => 'color-input',
                    'default' => '#d00909'
                ],
            ],
        ], */

        'agency' => [
            'title'         => 'Agency Application',
            'customization' => [],
        ],

        'oiart'   => [
            'title'         => 'Stepped Application',
            'customization' => [
                'primary_color' => [
                    'title'    => 'Primary Color',
                    'type'     => 'color-input',
                    'default'  => '#d00909',
                    'required' => true,
                ],

                'secondary_color' => [
                    'title'    => 'Secondary Color',
                    'type'     => 'color-input',
                    'default'  => '#d00909',
                    'required' => true,
                ],

                'tab_head_bg' => [
                    'title'    => 'Tab Head Background Color',
                    'type'     => 'color-input',
                    'default'  => '#7f7f7f',
                    'required' => true,
                ],

                'finish_button' => [
                    'title'    => 'Always Show Finish Button',
                    'type'     => 'select',
                    'default'  => 'Yes',
                    'data'     => ['Yes' => 'Yes', 'No' => 'No'],
                    'required' => true,
                ],
            ],
        ],
        'rounded' => [
            'title'         => 'Rounded Application',
            'customization' => [
                'primary_color' => [
                    'title'    => 'Primary Color',
                    'type'     => 'color-input',
                    'default'  => '#d00909',
                    'required' => true,
                ],

                'secondary_color' => [
                    'title'    => 'Secondary Color',
                    'type'     => 'color-input',
                    'default'  => '#d00909',
                    'required' => true,
                ],

                'tab_head_bg' => [
                    'title'    => 'Tab Head Background Color',
                    'type'     => 'color-input',
                    'default'  => '#7f7f7f',
                    'required' => true,
                ],

                'finish_button' => [
                    'title'    => 'Always Show Finish Button',
                    'type'     => 'select',
                    'default'  => 'Yes',
                    'data'     => ['Yes' => 'Yes', 'No' => 'No'],
                    'required' => true,
                ],
            ],
        ],
        'iframe' => [
            'title'         => 'Iframe Application',
            'customization' => [
                'primary_color' => [
                    'title'    => 'Primary Color',
                    'type'     => 'color-input',
                    'default'  => '#d00909',
                    'required' => true,
                ],

                'secondary_color' => [
                    'title'    => 'Secondary Color',
                    'type'     => 'color-input',
                    'default'  => '#d00909',
                    'required' => true,
                ],

                'tab_head_bg' => [
                    'title'    => 'Tab Head Background Color',
                    'type'     => 'color-input',
                    'default'  => '#7f7f7f',
                    'required' => true,
                ],

                'finish_button' => [
                    'title'    => 'Always Show Finish Button',
                    'type'     => 'select',
                    'default'  => 'Yes',
                    'data'     => ['Yes' => 'Yes', 'No' => 'No'],
                    'required' => true,
                ],

                'save_text' => [
                    'title'    => 'Save button text',
                    'type'     => 'text-input',
                    'default'  => 'Register',
                    'required' => true,
                ],
            ],
        ],
    ];

    public static function getSectionsList($sections)
    {
        foreach ($sections as $section) {
            $sectionslist[$section['id']] = $section['title'];
        }

        return $sectionslist;
    }

    public static function getSelectionData($data)
    {
        $list = [];
        foreach ($data as $key => $value) {
            $list[$value] = $value;
        }

        return $list;
    }

    public static function getContactFieldMap($object = 'student')
    {
        if ($object == 'student' || $object == 'form') {
            return [
                null     => 'Select Contact Field',
                'School' => [
                    'school|language' => 'School Language',
                ],

                'Student' => [
                    'student|first_name'      => 'Student\'s First Name',
                    'student|last_name'       => 'Student\'s Last Name',
                    'student|phone'           => 'Student\'s Phone',
                    'student|email'           => 'Student\'s Email',
                    'student|address'         => 'Student\'s Address',
                    'student|city'            => 'Student\'s City',
                    'student|country'         => 'Student\'s Country',
                    'student|postal_code'     => 'Student\'s Postal Code',
                    'student|phone'           => 'Student\'s Phone',
                    'params|campus'          => 'Student\'s Campus',
                    'params|program'          => 'Student\'s Program',
                ],

                'Parent'    => [
                    'parent|consent'    => 'Parent\'s Consent',
                    'parent|first_name' => 'Parent\'s First Name',
                    'parent|last_name'  => 'Parent\'s Last Name',
                    'parent|email'      => 'Parent\'s Email',

                ],
                'Booking'   => [
                    'booking|program'       => 'Booking Program',
                    'booking|startDates'    => 'Booking Start Dates',
                    'booking|coursePrice'   => 'Booking Courses Price',
                    'booking|weeks'         => 'Booking Selected Weeks',
                    'booking|programWeeks'  => 'Booking Program\'s Number of Weeks',
                    'booking|totalWeeks'    => 'Booking Total Weeks',
                    'booking|transfer'      => 'Booking Transfer',
                    'booking|transferPrice' => 'Booking Transfer Price',
                    'booking|miscellaneous' => 'Booking Miscellaneous',
                    'booking|miscPrice'     => 'Booking Miscellaneous Price',
                    'booking|extra'         => 'Booking Extra',
                    'booking|extraPrice'    => 'Booking Extra Price',
                    'booking|activity'      => 'Booking Activities',
                    'booking|excursion'     => 'Booking Excursions',
                    'booking|totalPrice'    => 'Booking Total Price',
                    'booking|campus'        => 'Booking Campus',
                ],
                'Calendar'  => [
                    'calendar|course_id'   => 'Calendar Course Id',
                    'calendar|course_name' => 'Calendar Course Name',
                    'calendar|start_date'  => 'Calendar Start Date',
                    'calendar|end_date'    => 'Calendar End Date',
                    'calendar|start_time'  => 'Calendar Start Time',
                    'calendar|end_time'    => 'Calendar End Time',
                    'calendar|date'        => 'Calendar Course Date',
                ],
                'Virtual Assistant' => [
                    'assistant|campus_title'     => 'Campus\' Title',
                    'assistant|campus_slug'      => 'Campus\' Slug',
                    'assistant|program_title'    => 'Program\'s Title',
                    'assistant|program_slug'     => 'Program\'s Slug',
                    'assistant|startDates'       => 'Start Dates',
                ],
                'URL Parameters' => [
                    'url|parameter'     => 'URL Parameter'
                ],
            ];
        }
        if ($object == 'agency') {
            return [
                null => 'Select Contact Field',
                'Agency' => [
                    'agency|name'  => 'Agency Name',
                    'agency|email' => 'Agency Email',
                ],
            ];
        }
    }

    public static function getCustomFieldMap($object = null)
    {
        $list = [null  => 'Select Custom Field'];
        // Get Custom Fields
        $customFields = CustomField::byObject($object);
        if ($object && !$customFields) {
            foreach ($customFields as $object=>$objectCustomField) {
                foreach ($objectCustomField as $customField) {
                    $list[ucwords($object)][$object ."|" . $customField['slug']] = $customField['name'];
                }
            }
        } elseif ($object && $customFields) {
            foreach ($customFields as $object=>$customField) {
                $list[$customField['slug']] = $customField['name'];
            }
        }
        return $list;
    }


    public static function slug($string, $model)
    {
        $item = $model::where('slug', Str::slug($string))->get();

        return Str::slug($string).'-'.rand(1, 100);
    }

    public static function getApplicationThemesList()
    {
        $list = [
            '0' => 'Select Layout',
        ];

        foreach (self::$application_themes as $slug => $theme) {
            $list[$slug] = $theme['title'];
        }

        return $list;
    }

    public static function getDefaultActions()
    {
        return self::$actions;
    }

    public static function getContactTypes()
    {
        return [
            'Candidat'      => 'Candidat',
            'Lead'          => 'Lead',
            'Applicant'     => 'Applicant',
            'Parent'        => 'Parent',
            'Student'       => 'Student',
            'Agent'         => 'Agent',
            'Agency'        => 'Agency',
            'Corporate'     => 'Corporate',
        ];
    }

    public static function getDateFormatList()
    {
        return [
            'YYYY-MM-DD'         => date('Y-m-d'),
            'MM-DD-YYYY'         => date('m-d-Y'),
            'DD-MM-YYYY'         => date('d-m-Y'),
            'YYYY/MM/DD'         => date('Y/m/d'),
            'MM/DD/YYYY'         => date('m/d/Y'),
            'DD/MM/YYYY'         => date('d/m/Y'),
            //'dddd, MMMM Do YYYY' => date("l, F j Y"),
            'DD.MM.YYYY'         => date('d.m.Y'),
            'MM.DD.YYYY'         => date('m.d.Y'),
        ];
    }

    public static function getApplication()
    {
        return Application::get()->pluck('title', 'id')->toArray();
    }

    public static function getApplicationsList($object = null , $publishedOnly = true , $by = null)
    {
        $applications =  Application::query();
        if($object){
            $applications->whereObject($object);
        }
        if($publishedOnly){
            $applications->wherePublished(true);
        }

        $applications = $applications->get();

        if($by)
        {
            $applications = $applications->pluck('title' , $by)->toArray();
        }
        return $applications;

    }

    public static function getAddons()
    {
        return Addon::get()->pluck('title', 'id')->toArray();
    }

    public static function getCampusesList($bySlug = false)
    {
        if(!$bySlug){
            return Campus::all()->pluck('title', 'id')->toArray();
        }else{
            return Campus::all()->pluck('title', 'slug')->toArray();
        }
    }

    public static function getCoursesList($campus_id = null, $file = null)
    {
        if ($campus_id) {
            $campus = Campus::with('courses')->findOrFail($campus_id);
            $courses = $campus->courses->pluck('title', 'slug')->toArray();
        } else {
            $courses = Course::all()->pluck('title', 'slug')->toArray();
        }

        $courses = self::filefilter($file, $courses);

        return $courses;
    }

    /**
     * @param $file
     * @param array $courses
     * @return array
     */
    protected static function filefilter($file, array $courses): array
    {
        if ($file) {
            $courses_selected_by_user_on_backend = Field::find($file)->data;
            if (! empty($courses_selected_by_user_on_backend)) {
                foreach ($courses as $key => $value) {
                    if (! array_key_exists($key, $courses_selected_by_user_on_backend)) {
                        unset($courses[$key]);
                    }
                }
            }
        }

        return $courses;
    }

    public static function getProgramsList($campus_id = null , $slug = null)
    {
        $params = isset($campus_id) ? ['col'=>'campus_id' , 'value' => $campus_id] : (isset($slug) ? ['col'=>'slug' , 'value' => $slug] : null) ;


        if($params){
            $programs = Program::whereHas('campuses' , function($q) use ($params){
                $q->where($params['col'] , $params['value']);
            })->pluck('title', 'slug')->toArray();
        }else{
            $programs = Program::all()->pluck('title', 'slug')->toArray();
        }
        return $programs;
    }

    public static function dateDataHandler($course, $field): array
    {
        $format_date = 'Y-m-d';
        $format_dateTime = 'Y-m-d h:i:s A';
        $format_time = 'h:i A';
        $schedules = Schedule::all()->keyBy('id')->toArray();
        $settings = session('settings-'.session('tenant'));

        //take date format also from field

        $datesData = [];
        foreach ($course->dates()->get() as $date) {
            $date_array = [
                'id'        => $date->id,
                'object_id' => $date->object_id,
                'key'       => $date->key,
            ];
            switch ($date->date_type) {
                case 'specific-dates':

                    $start =  Carbon::parse($date->properties['start_date'])->format($format_date);
                    $end =  Carbon::parse($date->properties['end_date'])->format($format_date);
                    $schedule = $schedules[$date->properties['date_schudel']];
                    $price  = number_format(str_replace("$" , "" , $date->properties['date_price']) , 2) .' '. $settings['school']['default_currency'];

                    $date_array['date'] = __('Start Date') . ": " . $start . "<br/>" . __('End Date') . ": " . $end . "<br/>" . __('Schedule') .": " . date('h:i a' , strtotime($schedule['start_time'])) ."-". date('h:i a' , strtotime($schedule['end_time'])) ."<br />" . __('Price') . ": " . $price;
                    break;
                case 'single-day':
                    $date_array['date'] = Carbon::parse(
                        $date->properties['date'].' '.$date->properties['start_time']
                    )->format($format_dateTime).' - '.Carbon::parse(
                        $date->properties['date'].' '.$date->properties['end_time']
                    )->format($format_dateTime);
                    break;

                case 'all-year':
                    $props = $date->properties;
                    $schedule = $schedules[$props['date_schudel']];
                    $date_array['date'] = $props['start_date'] . ' to ' . $props['end_date'] . ' '.
                    Carbon::parse($schedule['start_time'])->format($format_time).":".Carbon::parse($schedule['end_time'])->format($format_time);
                    break;

                default:
                    $date_array['date'] = Carbon::parse($date->properties['start_date'])->format($format_date);
                    break;
            }
            $datesData[] = $date_array;
        }

        return $datesData;
    }

    public static function getInvoiceCreationList()
    {
        return [
            'no-invoice'            => 'No Registration Fees',
            'auto_generate_invoice' => 'Create Invoice upon application start',
            'after_application_submitted' => 'Create Invoice after application submission',
            'after_application_approval' => 'Create Invoice after application approval',
        ];
    }

    public static function getFieldTypes()
    {
        return [

            'checkbox'  => [

                'title'      => 'CheckBox Group',

                'icon'       => 'fas fa-check-square',

                'field_type' => 'field',

            ],

            'singleCheckbox'    => [

                'title'      => 'Single CheckBox',

                'icon'       => 'fas fa-check-square',

                'field_type' => 'field',

            ],

            'date'  => [

                'title'      => 'Date',

                'icon'       => 'fas fa-calendar-alt',

                'field_type' => 'field',

            ],

            'datetime'  => [

                'title'         => 'Date & Time',

                'icon'          => 'fas fa-clock',

                'field_type'    => 'field',

            ],

            'file'  => [

                'title'      => 'File Uploader',

                'icon'       => 'fas fa-upload',

                'field_type' => 'file',

            ],

            'filesList' => [

                'title'      => 'Files List Uploader',

                'icon'       => 'fas fa-upload',

                'field_type' => 'file_list',
            ],

            'hidden'    => [

                'title'      => 'Hidden',

                'icon'       => 'fas fa-low-vision',

                'field_type' => 'field',

            ],

            'html'  => [

                'title'         => 'HTML',

                'icon'          => 'fab fa-html5',

                'field_type'    => 'html',

            ],

            'phone' => [

                'title'      => 'Phone',

                'icon'       => 'fas fa-phone',

                'field_type' => 'field',

            ],

            'email' => [

                'title'      => 'Email',

                'icon'       => 'fas fa-envelope',

                'field_type' => 'field',

            ],

            'list'  => [

                'title'      => 'List',

                'icon'       => 'fas fa-list',

                'field_type' => 'field',

            ],

            'loop'  => [

                'title'      => 'Fields Repeater',

                'icon'       => 'fas fa-redo',

                'field_type' => 'helper',

            ],

            'radio' => [

                'title'      => 'Radio Button',

                'icon'       => 'far fa-dot-circle',

                'field_type' => 'field',

            ],

            'text'  => [

                'title'      => 'One Line Text',

                'icon'       => 'fas fa-ellipsis-h',

                'field_type' => 'field',

            ],

            'textarea'  => [

                'title'      => 'Text',

                'icon'       => 'fas fa-text-width',

                'field_type' => 'field',

            ],

            'addon'  => [
                'title'      => 'Addons',
                'icon'       => 'mdi mdi-hexagon-multiple',
                'field_type' => 'field',
            ],

            'program'  => [
                'title'      => 'Programs',
                'icon'       => 'mdi mdi-hexagon-multiple',
                'field_type' => 'field',
            ],

            'course'  => [
                'title'      => 'Courses',
                'icon'       => 'mdi mdi-hexagon-multiple',
                'field_type' => 'field',
            ],

            'review'  => [
                'title'      => 'Application Review',
                'icon'       => 'mdi mdi-hexagon-multiple',
                'field_type' => 'helper',
            ],

            'cartReview'  => [
                'title'      => 'Cart Review',
                'icon'       => 'mdi mdi-hexagon-multiple',
                'field_type' => 'field',
            ],

            'paymentForm'  => [
                'title'         => 'Payment Form',
                'icon'          => 'fab fa-cc-stripe',
                'field_type'    => 'field',
            ],

            'signature' => [
                'title'      => 'Signature',
                'icon'       => 'fab fa-sass',
                'field_type' => 'field',
            ],
        ];
    }

    public static function getPaymentGateways()
    {
        return [

            'stripe'        => [
                    'title' => 'Stripe',
                    'icon'  => 'fab fa-cc-stripe',
            ],

            'paypal'    => [
                    'title' => 'PayPal',
                    'icon'  => 'fab fa-paypal',
            ],

            'helcim'    => [
                    'title' => 'Helcim',
                    'icon'  => 'fab fa-cc-visa',
            ],

            'moneris'   => [
                    'title' => 'Moneris',
                    'icon'  => 'fab fa-cc-visa',
            ],

            'flywire' => [
                'title' => 'Flywire',
                'icon' => 'fab fa-cc-visa',
            ],

            'authorize' => [
                'title' => 'AuthorizeNet',
                'icon' => 'fab fa-cc-visa',
            ],

            'datatrans' => [
                'title' => 'DataTrans',
                'icon' => 'fab fa-cc-visa',
            ],

            'creditagricole' => [
                'title' => 'Crédit Agricole',
                'icon' => 'fab fa-cc-visa',
            ],

            'bambora' => [
                'title' => 'Bambora',
                'icon' => 'fab fa-cc-visa',
            ],

        ];
    }

    public static function getIntegrations()
    {
        return [

            'webhook'       => [
                    'title' => 'Webhook',
                    'icon'  => 'fas fa-bolt',
            ],

            'mautic'    => [
                    'title' => 'Mautic',
                    'icon'   => 'fas fa-bolt',
            ],

            'salesforce' => [
                'title' => 'Salesforce',
                'icon' => 'fas fa-bolt',
            ],

            'hubspot'   => [
                    'title' => 'HubSpot',
                    'icon'   => 'fas fa-bolt',
            ],
            'campuslogin'   => [
                    'title' => 'CampusLogin',
                    'icon'   => 'fas fa-bolt',
            ],

        ];
    }

    public static function getActions()
    {
        return [

            'Redirect-To-Url'           => [
                    'title' => 'Redirect To URL',
                    'icon'  => 'fas fa-external-link-alt',
            ],

            'Send-Notification-Email'   => [
                    'title' => 'Send Notification Email',
                    'icon'  => 'fas fa-bell',
            ],

            'Send-Thank-You-Email'      => [
                    'title' => 'Send Thank You Email',
                    'icon'  => 'fas fa-envelope',
            ],

            'E-Signature'      => [
                'title' => 'E-Signature',
                'icon'  => 'fab fa-sass',
            ],
        ];
    }

    public static function stepPercentageProgress(int $step, Application $application)
    {
        $sections = count($application->sections_order);

        return ($step * 100) / $sections;
    }

    public static function getApplicationById($application_id)
    {
        return Application::find($application_id);
    }

    public static function getApplicationFields($application)
    {
        $list = [];
        $sections = $application->sections()->with('fields')->get();
        foreach ($sections as $section) {
            $fields = $section->fields->pluck('label', 'name')->toArray();
            $list += $fields;
        }
        return $list;
    }

    public static function getCustomFieldParams($fieldProps, $customField, $value, $field)
    {
        if ($customField['field_type'] == 'list') {
            $data = array_combine($customField['data']['values'], $customField['data']['labels']);
        } else {
            $data = null;
        }
        $props = [
            'name'          => $field->name."[".$fieldProps['name']."]",
            "type"          => $customField['field_type'],
            "map"           => null,
            "editable"      => (bool) $fieldProps['editable'],
            "hidden"        => (bool) $fieldProps['hidden'],
            "contactType"   => "lead",
            "default"       => null,
            "placeholder"   => null,
            "helper"        => null,
            "class"         => null,
            "attr"          => null,
            'value'         => $value,
            "wrapper" => [
                "columns" =>  "6",
                "class"   =>  null,
                "attr"   => null
            ],
            "label" =>  [
                "show"  =>  true,
                "text"  =>  $customField['name'],
                "class" => null,
                "attr"  => null
            ],
            "smart" => null,
            "show"  => true,
            "data"  => $data
        ];
        return $props;
    }

    public static function getApplicationUrl($application , $school , $settings = [])
    {
        if(empty($settings))
        {
            $settings = Setting::byGroup();
        }

        if(isset($settings['school']['domain'])){
           return sprintf("%s/applications/%s", $settings['school']['domain'] , $application->slug);
        }
        return route($application->route , ['school'=> $school , 'application' => $application] );

    }
}
