<?php

namespace Tests\Unit\Promocodes;

use App\Promocodes;
use App\Repository\PromocodeRepository;
use Mockery;
use Tests\TestCase;

class OutputRandomPromocodesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(PromocodeRepository::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_will_output_only_one_code_without_parameter()
    {
        $this->repository->shouldReceive('pluck')
            ->andReturn(collect([1, 2, 3]));

        $promocodes = (new Promocodes($this->repository))->output();

        $this->assertCount(1, $promocodes);
    }

    /** @test */
    public function it_can_output_several_promocodes_as_array()
    {
        $this->repository->shouldReceive('pluck')
            ->andReturn(collect([1, 2, 3]));

        $promocodes = (new Promocodes($this->repository))->output(10);

        $this->assertCount(10, $promocodes);
    }

    /** @test */
    public function it_generates_code_with_suggested_code_and_ignore_mask()
    {
        $this->repository->shouldReceive('pluck')
            ->andReturn(collect([1, 2, 3]));

        $promocodes = (new Promocodes($this->repository))->output(1, 'SUMMER-2020');
        $promocode = $promocodes[0];

        $this->assertEquals('SUMMER-2020', $promocode);
    }

    /** @test */
    public function suggested_code_can_not_be_duplicated()
    {
        $this->repository->shouldReceive('pluck')
            ->andReturn(collect(['SUMMER-2020']));

        $promocodes = (new Promocodes($this->repository))->output(1, 'SUMMER-2020');
        $promocode = $promocodes[0];

        $this->assertNotEquals('SUMMER-2020', $promocode);
    }
}
