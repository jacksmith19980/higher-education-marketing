<?php

namespace Tests\Unit\Helpers\Quotation;

use Tests\TestCase;

class QuotationHelpersTest extends TestCase
{
    /** @test */
    public function when_pass_a_date_time_time_to_formateDateStarTimeEndTime_return_correct_format()
    {
        $formated = \App\Helpers\Quotation\QuotationHelpers::formateDateStarTimeEndTime('2020-01-22 13:02 19:57');

        $this->assertEquals($formated, 'Wednesday 22nd of January 2020 (1:02 pm to 7:57 pm)');
    }

    /** @test */
    public function when_pass_time_to_amOrPm_calculate_correct_time_and_Am_PM()
    {
        $time1 = \App\Helpers\Quotation\QuotationHelpers::amOrPm('12:00');
        $time2 = \App\Helpers\Quotation\QuotationHelpers::amOrPm('24:00');
        $time3 = \App\Helpers\Quotation\QuotationHelpers::amOrPm('01:00');
        $time4 = \App\Helpers\Quotation\QuotationHelpers::amOrPm('11:00');
        $time5 = \App\Helpers\Quotation\QuotationHelpers::amOrPm('15:00');
        $time6 = \App\Helpers\Quotation\QuotationHelpers::amOrPm('19:00');

        $this->assertEquals($time1, '12:00 pm');
        $this->assertEquals($time2, '12:00 am');
        $this->assertEquals($time3, '1:00 am');
        $this->assertEquals($time4, '11:00 am');
        $this->assertEquals($time5, '3:00 pm');
        $this->assertEquals($time6, '7:00 pm');
    }
}
