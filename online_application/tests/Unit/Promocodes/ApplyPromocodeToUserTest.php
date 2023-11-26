<?php

namespace Tests\Unit\Promocodes;

use App\Exceptions\UnauthenticatedException;
use App\Promocodes;
use App\Repository\PromocodeRepository;
use App\Tenant\Models\Promocode;
use Mockery;
use Tests\TestCase;

class ApplyPromocodeToUserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(PromocodeRepository::class);
        $this->repository->shouldReceive('pluck')
            ->andReturn(collect([1, 2, 3]));
        $this->user = \App\Tenant\Models\Student::factory()->make();
        $this->repository->shouldReceive('byCode')
            ->andReturn($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_throws_exception_if_user_is_not_authenticated()
    {
        $this->expectException(UnauthenticatedException::class);

        $this->repository->shouldReceive('insert')->once()
            ->andReturn(true);
        $promocodes = (new Promocodes($this->repository))->create();
        $promocode = $promocodes->first();

        $this->assertCount(1, $promocodes);

        (new Promocodes($this->repository))->apply($promocode['code']);
    }

    /** @test */
    public function it_returns_false_if_promocode_doesnt_exist()
    {
        $this->actingAs($this->user);

        $this->repository->shouldReceive('first')
            ->andReturn(null);

        $appliedPromocode = (new Promocodes($this->repository))->apply('INVALID-CODE');

        $this->assertFalse($appliedPromocode);
    }

    /** @test */
    public function user_can_apply_code_twice()
    {
        $this->actingAs($this->user);

        $promocode = $this->createPromo();

        $this->repository->shouldReceive('first')
            ->andReturn($promocode);

        $this->repository->shouldReceive('isSecondUsageAttempt')
            ->andReturn(true);
        $this->repository->shouldReceive('load')
            ->andReturn($promocode);

        (new Promocodes($this->repository))->apply($promocode['code']);
    }

    /**
     * @return mixed
     */
    protected function createPromo(): Promocode
    {
        $this->repository->shouldReceive('insert')
            ->andReturn(true);

        $promocodes = (new Promocodes($this->repository))->create();
        $promocodeArr = $promocodes->first();
        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertCount(1, $promocodes);

        return $promocode;
    }
}
