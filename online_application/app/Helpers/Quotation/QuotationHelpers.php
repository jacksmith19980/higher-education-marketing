<?php

namespace App\Helpers\Quotation;

use App\Helpers\School\PromocodeHelpers;
use App\Repository\PromocodeRepository;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Promocode;
use App\Tenant\Models\Quotation;
use App\Tenant\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Response;

class QuotationHelpers
{
    /**
     * Return List of Currencies
     */
    public static function getAddonsList()
    {
        return [
            'activity'  => 'Activity',
            'material'  => 'Material',
            'excursion' => 'Excursion',
        ];
    }

    public static function getDefaultFlowOrder()
    {
        return [
            'campus'     => 'campus',
            'program'    => 'program',
            'addon'      => 'addon',
        ];
    }

    /**
     * Return List of Currencies
     */
    public static function getDefaultCurrency()
    {
        return [
            '$'     => 'US Dollar',
            '£'     => 'Great Britain Pound',
            '€'     => 'Euro',
            'CAD'   => 'Canadian Dollar',
            'CHF'   => 'Swiss Franc',
            'AUD'   => 'Australian Dollar',
        ];
    }

    /**
     * Return List of Currencies
     */
    public static function getCurrencyString($symbol = null)
    {
        $list = [
            'CAD'   => 'cad',
            '$'     => 'usd',
            '£'     => 'gbp',
            '€'     => 'euro',
            'CHF'   => 'chf',
        ];
        if (! $symbol || ! isset($list[$symbol])) {
            return $symbol;
        }

        return $list[$symbol];
    }

    /**
     * Get Selected Campuses to display in quotation
     */
    public static function getCampusSelection($campusIDs)
    {
        if ($campuses = Campus::find($campusIDs)->pluck('title', 'id')->toArray()) {
            return $campuses;
        }

        return [];
    }

    /**
     * Return Pricing Template for Accommodation, Transfer, And Misc.
     */
    public static function getPricingOptions()
    {
        return [
            'fixed-price'   => 'Fixed Price',
            'weekly'        => 'Per Week',
            'monthly'       => 'Per Month',
        ];
    }

    /**
     * get Quotation Dates by campuses and courses
     */
    public static function getQuotationDates($courses, $campuses)
    {
        $list = [];
        $courses = Course::find($courses);

        foreach ($courses as $course) {
            foreach ($course->properties['start_date'] as $key => $startDate) {
                $endDates = $course->properties['end_date'];
                $val = $startDate.':'.$endDates[$key];
                $startDate = date('l jS \of F Y', strtotime($startDate));

                $endDate = date('l jS \of F Y', strtotime($endDates[$key]));
                $dates = $startDate.' - '.$endDate;
                $list[$val] = $dates;
            }
        }

        return $list;
    }

    /**
     * Get Selected Courses to Display in Quotation
     */
    public static function getCoursesSelection($courseIDs, $campusesId = null)
    {
        $list = [];
        $courses = Course::whereIn('id', $courseIDs)->with('campuses')->get()->toArray();

        foreach ($courses as $course) {
            $ids = array_column($course['campuses'], 'id');
            if (array_intersect($ids, $campusesId)) {
                $list[$course['slug']] = $course['title'];
            }
        }

        return $list;
    }

    /**
     * Return Start and End Date Selection
     */
    public static function getSpecificDatesSelect($startDates, $endDates)
    {
        $list = [];
        foreach ($startDates as $key => $startDate) {
            $val = $startDate.':'.$endDates[$key];
            $startDate = date('l jS \of F Y', strtotime($startDate));

            $endDate = date('l jS \of F Y', strtotime($endDates[$key]));

            $dates = $startDate.' - '.$endDate;
            $list[$val] = $dates;
        }

        return $list;
    }

    public static function carbon($datetime = null)
    {
        if ($datetime instanceof \Carbon\Carbon) {
            return $datetime;
        }

        if ($datetime instanceof \DateTime) {
            $datetime = $datetime->format('Y-m-d H:i:s');
        }

        return new \Carbon\Carbon($datetime);
    }

    /**
     * Get List of Campuses
     */
    public static function getCampusesSelection($campusIDs = null)
    {
        if ($campuses = Campus::find($campusIDs)->pluck('title', 'id')->toArray()) {
            return $campuses;
        }

        return [];
    }

    /**
     * Get Accommodation Options with Price
     */
    public static function getAccommodationOption($quotation)
    {
        $list = self::getOptionsWithPrice($quotation['accomodation_options'], $quotation['accomodation_options_price']);

        return $list;
    }

    /**
     * Get Transfer Options with Price
     */
    public static function getTransferOption($quotation)
    {
        return self::getOptionsWithPrice($quotation['transfer_options'], $quotation['transfer_options_price']);
    }

    protected static function getOptionsWithPrice($options, $prices)
    {
        $list = [];

        foreach ($options as $key => $option) {
            $list[$key.'_'.$prices[$key]] = $option;
        }

        return $list;
    }

    /**
     * Get Week's Start Day in Numbers
     */
    public static function getNumericStartDate($day)
    {
        return 1;
    }

    /**
     * Get Disabled Days based on Week Start and Week End
     */
    public static function getDisabledDays(array $dates)
    {
        $days = [0, 1, 2, 3, 4, 5, 6];

        $disabled = array_diff($days, $dates);

        return implode(',', $disabled);
    }

    /**
     * Get Course Minimum Days based on how many weeks
     */
    public static function getCourseMinDays($numOfWeeks)
    {
        if ($numOfWeeks == 1) {
            return self::numberOfWeekDays();
        } elseif ($numOfWeeks > 1) {
            $firstWeek = self::numberOfWeekDays();

            $rest = ($numOfWeeks - 1) * 7;

            return $firstWeek + $rest;
        }

        return $numOfWeeks;
    }

    /**
     * Get Number of Days for School based on Start and End Days ( Default Monday to Friday == 5 Days )
     */
    public static function numberOfWeekDays()
    {

        // @TODO to Calculate the actual Week Days

        return 5;
    }

    public static function formateStartEndDates($dates, $separator = ':')
    {
        $tmp = explode($separator, $dates);

        foreach ($tmp as $key => $date) {
            $tmp[$key] = date('l jS \of F Y', strtotime($date));
        }

        return implode(' to ', $tmp);
    }

    // New method for Course Drop download
    public static function formateCourseStartEndDates($dates, $separator, $schedule)
    {
        $tmp = explode($separator, $dates);
        foreach ($tmp as $key => $date) {
            if($date){
                //September 18, 2023
                $tmp[$key] = date('F d, Y', strtotime($date));
            }
        }
        $date = $tmp[0] . ((isset($tmp[1]) && !empty($tmp[1])) ?  __(' to ') .$tmp[1] : '')  . ' - ' . $schedule;
        return $date;
    }

    /**
     * Format Date Time Time to better human read
     *
     * @param $date_time_time
     * @param string $separator
     * @return string
     */
    public static function formateDateStarTimeEndTime($date_time_time, $separator = ' ')
    {
        $tmp = explode($separator, $date_time_time);

        $tmp[0] = date('l jS \of F Y', strtotime($tmp[0]));
        $tmp[1] = self::amOrPm($tmp[1]);
        $tmp[2] = self::amOrPm($tmp[2]);

        return $tmp[0].' ('.$tmp[1].' to '.$tmp[2].')';
    }

    /**
     * From 13:00 return 1:00 PM or 2:00 return 2:00 AM
     * @param $time
     * @return string
     */
    public static function amOrPm($time)
    {
        return date('g:i a', strtotime($time.' UTC'));
    }

    /** get Number of Weeks in High Season
     * @param $hs_start
     * @param $hs_end
     * @param $start
     * @param $end
     * @return int
     */
    public static function weeksInHighSeason($hs_start, $hs_end, $start, $end)
    {
        //@TODO Calculate number of weeks in HighSeason

        return 0;
    }

    public static function moneyFormat($number, $format = '%i')
    {
        $regex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.

            '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';

        if (setlocale(LC_MONETARY, 0) == 'C') {
            setlocale(LC_MONETARY, '');
        }

        $locale = localeconv();

        preg_match_all($regex, $format, $matches, PREG_SET_ORDER);

        foreach ($matches as $fmatch) {
            $value = floatval($number);

            $flags = [

                'fillchar' => preg_match('/\=(.)/', $fmatch[1], $match) ?

                    $match[1] : ' ',

                'nogroup' => preg_match('/\^/', $fmatch[1]) > 0,

                'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?

                    $match[0] : '+',

                'nosimbol' => preg_match('/\!/', $fmatch[1]) > 0,

                'isleft' => preg_match('/\-/', $fmatch[1]) > 0,

            ];

            $width = trim($fmatch[2]) ? (int) $fmatch[2] : 0;

            $left = trim($fmatch[3]) ? (int) $fmatch[3] : 0;

            $right = trim($fmatch[4]) ? (int) $fmatch[4] : $locale['int_frac_digits'];

            $conversion = $fmatch[5];

            $positive = true;

            if ($value < 0) {
                $positive = false;

                $value *= -1;
            }

            $letter = $positive ? 'p' : 'n';

            $prefix = $suffix = $cprefix = $csuffix = $signal = '';

            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];

            switch (true) {
                case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                    $prefix = $signal;

                    break;

                case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                    $suffix = $signal;

                    break;

                case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                    $cprefix = $signal;

                    break;

                case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                    $csuffix = $signal;

                    break;

                case $flags['usesignal'] == '(':
                case $locale["{$letter}_sign_posn"] == 0:
                    $prefix = '(';

                    $suffix = ')';

                    break;
            }

            if (! $flags['nosimbol']) {
                $currency = $cprefix.($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']).

                    $csuffix;
            } else {
                $currency = '';
            }

            $space = $locale["{$letter}_sep_by_space"] ? ' ' : '';

            $value = number_format(
                $value,
                $right,
                $locale['mon_decimal_point'],
                $flags['nogroup'] ? '' : $locale['mon_thousands_sep']
            );

            $value = @explode($locale['mon_decimal_point'], $value);

            $n = strlen($prefix) + strlen($currency) + strlen($value[0]);

            if ($left > 0 && $left > $n) {
                $value[0] = str_repeat($flags['fillchar'], $left - $n).$value[0];
            }

            $value = implode($locale['mon_decimal_point'], $value);

            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix.$currency.$space.$value.$suffix;
            } else {
                $value = $prefix.$value.$space.$currency.$suffix;
            }

            if ($width > 0) {
                $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?

                    STR_PAD_RIGHT : STR_PAD_LEFT);
            }

            $format = str_replace($fmatch[0], $value, $format);
        }

        return $format;
    }

    public static function getPaymentBadge($status)
    {
        switch ($status) {
            case 'Paid':
                return "<h4><span class='label label-success'>".__($status).'</span></h4>';
                break;

            case 'Invoice Created':
                return "<a><h4><span class='label label-info'>".__($status).'</span></h4></a>';
                break;

            default:
                // code...
                break;
        }
    }

    /**
     * Construct Booking details
     *
     * @param array $details
     * @return string
     */
    public static function getBookingDetails($details)
    {
        $html = '';
        foreach ($details['courses'] as $course) {
            $html .= '<h4><strong>'.$course['title'].'</strong></h4>';
            foreach ($course['dates'] as $date) {
                $html .= '<span style="display:block;maring:10px 0;">'.self::formateStartEndDates(
                    $date['start'].':'.$date['end']
                ).'</span>';
            }
        }

        return $html;
    }

    public static function formatDate($date, $format = 'l j F Y')
    {
        $date = self::carbon($date);

        return $date->format($format);
    }

    /**
     * Return Addon by Group and ordered
     *
     * @param array $addons
     * @return array
     */
    public static function addonsByGroup($addons)
    {
        // Order
        $cats = ['excursion', 'activity', 'material'];

        $grouped = [];
        foreach ($cats as $cat) {
            foreach (array_reverse($addons) as $key => $addon) {
                if ($cat == $addon['category']) {
                    $grouped[$addon['category']][$key] = $addon;
                }
            }
        }

        return $grouped;
    }

    /**
     * Return Addon Title
     *
     * @param string $title
     * @return string
     */
    public static function addonsTitle($title)
    {
        switch ($title) {
            case 'activity':
                return 'Extra Activities';
                break;

            case 'excursion':
                return 'Excursions';
                break;

            case 'material':
                return 'Material';
                break;

            default:
                return Str::plural(ucwords($title), 10);
                break;
        }
    }

    public static function factoryRegistrationQuotation($account)
    {
        switch ($account) {
            case 'parent':
            case 'student':
                return new StudentRegistrationQuotationHelper();
                break;
            case 'agent':
                return new AgentRegistrationQuotationHelper();
                break;
            default:
                return false;
        }
    }

    /**
     * Return Quotations Settings
     * @return mixed
     */
    public static function getQuotationsSettings()
    {
        return Setting::byGroup('quotation')['quotation'];
    }

    public static function accountsAllowedBooking($properties): array
    {
        $accounts_booking = [];
        if (! isset($properties['account_type'])) {
            return ['student' => 'Student'];
        }
        foreach ($properties['account_type'] as $type) {
            $accounts_booking[strtolower($type)] = ucfirst($type);
        }

        return $accounts_booking;
    }

    /**
     * Calculate Final Price and modifiy Cart
     *
     * @return array
     */
    public static function calculatePrice($courses, $quotation, $cart, $promo_repository, $code = null)
    {
        $price = [
            'courses'       => 0,
            'addons'        => 0,
            'weeks'         => 0,
            'accomodations' => 0,
            'transfer'      => 0,
            'miscellaneous' => 0,
            'fee'           => 0,
            'total'         => 0,
        ];

        if (isset($cart['courses'])) {
            foreach ($cart['courses'] as $key => $course) {
                $dateTotal = self::calculateCourseAndAddonsPrice($course['dates']);

                $cart['courses'][$key]['courses_total'] = $dateTotal['courses_total'];
                $cart['courses'][$key]['addons_total'] = $dateTotal['addons_total'];
                $cart['courses'][$key]['fee'] = self::calculateFeePrice($course);
                $cart['courses'][$key]['total'] = $dateTotal['total'] + $cart['courses'][$key]['fee'];
                $cart['courses'][$key]['campus_title'] = Campus::find($cart['courses'][$key]['campus'])->title;
                $cart['courses'][$key]['totalWeeks'] = self::getNumberOfBookedWeeks($course['dates']);

                $price['courses'] += $cart['courses'][$key]['courses_total'];
                $price['addons'] += $cart['courses'][$key]['addons_total'];
                $price['weeks'] += $cart['courses'][$key]['totalWeeks'];
                $price['fee'] += $cart['courses'][$key]['fee'];
            }
        }

        if (isset($cart['transfer'])) {
            $price['transfer'] = self::caluculateTransferPrice($cart['transfer']);
        }

        if (isset($cart['miscellaneous'])) {
            $price['miscellaneous'] = self::calculateMiscellaneousPrice($cart['miscellaneous']);
        }

        if (isset($cart['accomodations'])) {
            $price['accomodations'] = self::caluculateAccommodationPrice($cart['accomodations']);
        }

        $price['total'] = array_sum($price) - $price['weeks'];

        $cart['weeks'] = $price['weeks'];

        list($price, $cart) = self::applyPromoDicountToQuotation(
            $cart,
            $quotation,
            $price,
            $promo_repository,
            $code
        );

        $cart['price'] = $price;

        return [$price,  $cart];
    }

    private static function calculateFeePrice($course)
    {
        if (isset($course['fee']) && is_numeric($course['fee'])) {
            return $course['fee'];
        }
    }

    /**
     * Calculate course and addons Price
     *
     * @param array $dates
     * @return array
     */
    protected static function calculateCourseAndAddonsPrice($dates = [])
    {
        $total = 0;
        $courses_total = 0;
        $addons_total = 0;

        foreach ($dates as $key => $date) {
            $dates[$key]['price'] = $date['price'];
            $dates[$key]['addons_price'] = (! empty($date['addons'])) ? self::getDateAddonPrice($date) : 0;
            $dates[$key]['total'] = $dates[$key]['price'] + $dates[$key]['addons_price'];

            $courses_total += $dates[$key]['price'];
            $addons_total += $dates[$key]['addons_price'];
            $total += $dates[$key]['total'];
        }

        return [
            'dates' => $dates,
            'total' => $total,
            'courses_total' => $courses_total,
            'addons_total' => $addons_total,
        ];
    }

    /**
     * Get Date's Addons Price
     *
     * @param [type] $date
     */
    protected static function getDateAddonPrice($date)
    {
        $addonsPirce = 0;

        foreach ($date['addons'] as $group => $addons) {
            foreach ($addons as $addon) {
                $price = (isset($addon['price']) && is_numeric($addon['price'])) ? $addon['price'] : 0;
                if ($addon['price_type'] == 'weekly') {
                    $addonsPirce += self::getWeeklyPrice($date['start'], $date['end'], $price);
                }

                if ($addon['price_type'] == 'fixed-price') {
                    $addonsPirce += $price;
                }
            }
        }

        return $addonsPirce;
    }

    protected static function getWeeklyPrice($start, $end, $price)
    {
        $days = strtotime($start) - strtotime($end);
        $numberOfDays = ($days / (60 * 60 * 24)) + 1;
        $weeks = self::getNumberofWeeks($numberOfDays);

        if (is_numeric($price)) {
            return $price * $weeks;
        }

        return 0;
    }

    protected static function getNumberofWeeks($numberOfDays)
    {
        $numberOfWeeksDays = self::numberOfWeekDays();
        if (($rest = $numberOfDays - $numberOfWeeksDays) <= 0) {
            $numberOfWeeks = 1;
        } else {
            $numberOfWeeks = ($rest / 7) + 1;
        }

        return $numberOfWeeks;
    }

    /**
     * Caluculate Transfer Price
     *
     * @param array $transfer
     * @return int
     */
    protected static function caluculateTransferPrice($transfer = null)
    {
        $transferPrice = 0;

        if ($transfer) {
            foreach ($transfer as $item) {
                $transferPrice += $item['price'];
            }
        }

        return $transferPrice;
    }

    /**
     * Now same as caluculateTransferPrice but it must be improve to calculate per weeks
     * @param null $miscellaneous
     * @return int|mixed
     */
    private static function calculateMiscellaneousPrice($miscellaneous = null)
    {
        $miscPrice = 0;

        if ($miscellaneous) {
            foreach ($miscellaneous as $item) {
                $miscPrice += $item['price'];
            }
        }

        return $miscPrice;
    }

    /**
     * Caluculate Transfer Price
     *
     * @param array $transfer
     * @return int
     */
    protected static function caluculateAccommodationPrice($accommodation = null)
    {
        $accommodationPrice = 0;
        if ($accommodation) {
            foreach ($accommodation as $item) {
                $accommodationPrice += $item['price'];
            }
        }

        return $accommodationPrice;
    }

    private static function applyPromoDicountToQuotation($cart, $quotation, $price, $promo_repository, $code): array
    {
        if (! $discount = self::getPromocodeDiscount($quotation, $promo_repository, $code)) {
            return [$price, $cart];
        }

        $cart['discount'] = $discount->toArray();

        $price['discount'] = PromocodeHelpers::calculateDiscount($cart, $price);

        $price['total_before_discount'] = $price['total'];
        $price['total'] = $price['total_before_discount'] - $price['discount'];

        return [$price, $cart];
    }

    /**
     * Get Number For Booked Weeks
     */
    protected static function getNumberOfBookedWeeks($dates)
    {
        $totalWeeks = 0;
        foreach ($dates as $date) {
            $days = strtotime($date['start']) - strtotime($date['end']);
            $numberOfDays = ($days / (60 * 60 * 24)) + 1;
            $weeks = self::getNumberofWeeks($numberOfDays);
            $totalWeeks += $weeks;
        }

        return $totalWeeks;
    }

    private static function getPromocodeDiscount(Quotation $quotation, $promo_repository, $code)
    {
        $quotationsPromocodes = self::getQuotationAutomaticsPromocode($quotation);
        $globalAutomaticsPromocodes = self::getAutomaticsGlobalsPromocode($promo_repository);

        return $code ?? self::selectHigherPromocode($quotationsPromocodes->concat($globalAutomaticsPromocodes));
    }

    private static function getQuotationAutomaticsPromocode(Quotation $quotation): Collection
    {
        return $quotation->load('promocodeables')->promocodeables->filter(function ($promocode) {
            return $promocode->is_automatic;
        });
    }

    private static function getAutomaticsGlobalsPromocode(PromocodeRepository $promo_repository): Collection
    {
        return $promo_repository->getGlobalPromocodes()->filter(function ($code, $key) {
            return $code->is_automatic;
        });
    }

    private static function selectHigherPromocode(Collection $promocodes)
    {
        return $promocodes->sortByDesc('reward')->first();
    }
}
