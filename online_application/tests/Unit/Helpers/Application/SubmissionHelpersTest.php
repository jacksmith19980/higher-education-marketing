<?php

namespace Tests\Unit\Helpers\Application;

use App\Helpers\Application\SubmissionHelpers;
use Tests\TestCase;

class SubmissionHelpersTest extends TestCase
{
    /** @test */
    public function extract_system_language()
    {
        $this->assertEquals('en', SubmissionHelpers::extractSchoolDetails('language'));
    }
}
