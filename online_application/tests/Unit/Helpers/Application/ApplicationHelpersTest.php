<?php

namespace Tests\Unit\Helpers\Application;

use App\Helpers\Application\ApplicationHelpers;
use Tests\TestCase;

class ApplicationHelpersTest extends TestCase
{
    /** @test */
    public function return_language()
    {
        $this->assertArrayHasKey('school|language', ApplicationHelpers::getContactFieldMap());
    }
}
