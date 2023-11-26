<?php

namespace Tests\Unit\Promocodes;

use App\Exceptions\InvalidPromocodeException;
use App\Promocodes;
use App\Repository\PromocodeRepository;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;

class DisablePromocodeTest extends TestCase
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
    public function it_thows_exception_if_promocode_is_invalid()
    {
        $this->expectException(InvalidPromocodeException::class);

        $this->repository->shouldReceive('pluck')->times(1)
            ->andReturn(collect([1, 2, 3]));

        $this->repository->shouldReceive('byCode')->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('first')->once()
            ->andReturn(null);

        (new Promocodes($this->repository))->disable('INVALID-CODE');
    }

    /** @test */
    public function it_sets_commence_date_if_code_was_valid()
    {
        $this->repository->shouldReceive('pluck')->times(1)
            ->andReturn(collect([1, 2, 3]));

        $this->repository->shouldReceive('insert')->once()
            ->andReturn(true);

        $promocodes = (new Promocodes($this->repository))->create(
            1,
            25,
            null,
            [],
            null,
            null,
            1,
            true
        );
        $promocodeArr = $promocodes->first();

        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertNotNull($promocode->commence_at);
        $this->assertEquals(Carbon::now()->format('Y-m-d'), $promocode->commence_at->format('Y-m-d'));
    }

    /** @test */
    public function it_sets_expiration_date_if_code_was_valid()
    {
        $this->repository->shouldReceive('pluck')->times(1)
            ->andReturn(collect([1, 2, 3]));

        $this->repository->shouldReceive('insert')->once()
            ->andReturn(true);

        $promocodes = (new Promocodes($this->repository))->create(
            1,
            25,
            null,
            [],
            null,
            null,
            1,
            true
        );
        $promocodeArr = $promocodes->first();

        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertNotNull($promocode->expires_at);
        $this->assertEquals(
            Carbon::now()->addDays(config('promocodes.expires_in'))->format('Y-m-d'),
            $promocode->expires_at->format('Y-m-d')
        );
    }

    /** @test */
    public function it_returns_true_if_disabled()
    {
        $this->repository->shouldReceive('pluck')->times(2)
            ->andReturn(collect([1, 2, 3]));

        $this->repository->shouldReceive('insert')->once()
            ->andReturn(true);

        $promocodes = (new Promocodes($this->repository))->create(
            1,
            25,
            null,
            [],
            30,
            1,
            true
        );

        $promocodeArr = $promocodes->first();

        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->repository->shouldReceive('byCode')->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('first')->once()
            ->andReturn($promocode);

        $this->repository->shouldReceive('save')->once()
            ->andReturn(true);

        $disabledPromocode = (new Promocodes($this->repository))->disable($promocode['code']);

        $this->assertTrue($disabledPromocode);
    }
}
