<?php

namespace Tests\Unit\Promocodes;

use App\Exceptions\InvalidPromocodeException;
use App\Promocodes;
use App\Repository\PromocodeRepository;
use App\Tenant\Models\Promocode;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;

class CheckPromocodeValidationTest extends TestCase
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
    public function it_returns_promocode_model_if_validation_passes()
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
            null,
            30,
            1,
            true
        );
        $promocodeArr = $promocodes->first();

        $this->assertCount(1, $promocodes);

        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->repository->shouldReceive('byCode')->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('first')->once()
            ->andReturn($promocode);

        $this->repository->shouldReceive('promocodeusableExist')->once()
            ->andReturn(false);

        $checkPromocode = (new Promocodes($this->repository))->check($promocode['code']);

        $this->assertTrue($checkPromocode instanceof Promocode);
        $this->assertEquals($promocode['code'], $checkPromocode->code);
    }

    /** @test */
    public function it_throws_exception_if_there_is_not_such_promocode()
    {
        $this->expectException(InvalidPromocodeException::class);

        $this->repository->shouldReceive('pluck')->times(1)
            ->andReturn(collect([1, 2, 3]));

        $this->repository->shouldReceive('byCode')->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('first')->once()
            ->andReturn(null);

        (new Promocodes($this->repository))->check('INVALID-CODE');
    }

    /** @test */
    public function it_returns_false_if_promocode_is_expired()
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

        $promocodeArr['expires_at'] = Carbon::now()->subDays(1);

        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertCount(1, $promocodes);

        $this->repository->shouldReceive('byCode')->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('first')->once()
            ->andReturn($promocode);

        $checkPromocode = (new Promocodes($this->repository))->check($promocode['code']);

        $this->assertFalse($checkPromocode);
        $this->assertFalse($promocode->isActive());
    }

    /** @test */
    public function it_returns_false_if_promocode_not_commence()
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

        $promocodeArr['commence_at'] = Carbon::tomorrow();

        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertCount(1, $promocodes);

        $this->repository->shouldReceive('byCode')->once()
            ->andReturn($this->repository);

        $this->repository->shouldReceive('first')->once()
            ->andReturn($promocode);

        $checkPromocode = (new Promocodes($this->repository))->check($promocode['code']);

        $this->assertFalse($checkPromocode);
        $this->assertFalse($promocode->isActive());
    }

    /** @test */
    public function it_returns_false_if_promocode_exceeds_quantity()
    {
        $this->repository->shouldReceive('pluck')->andReturn(collect([1, 2, 3]));

        $this->repository->shouldReceive('insert')->once()
            ->andReturn(true);

        $promocodes = (new Promocodes($this->repository))->create(
            1,
            null,
            null,
            [],
            null,
            null,
            2
        );
        $promocodeArr = $promocodes->first();
        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertCount(1, $promocodes);

        $this->be(\App\Tenant\Models\Student::factory()->make());
        $this->repository->shouldReceive('byCode')->andReturn($this->repository);

        $this->repository->shouldReceive('first')->andReturn($promocode);

        $this->repository->shouldReceive('isSecondUsageAttempt')
            ->andReturn(false);

        $this->repository->shouldReceive('save')
            ->andReturn(true);

        $this->repository->shouldReceive('load')
            ->andReturn($promocode);

        $appliedPromocode = (new Promocodes($this->repository))->apply($promocode['code']);
        $this->assertNotFalse($appliedPromocode);

        $appliedPromocode = (new Promocodes($this->repository))->apply($promocode['code']);
        $this->assertNotFalse($appliedPromocode);

        $appliedPromocode = (new Promocodes($this->repository))->apply($promocode['code']);
        $this->assertFalse($appliedPromocode);
    }

    /** @test */
    public function it_returns_false_if_promocode_is_disposable_and_used()
    {
        $this->repository->shouldReceive('pluck')->andReturn(collect([1, 2, 3]));
        $this->repository->shouldReceive('insert')->once()
            ->andReturn(true);

        $promocodes = (new Promocodes($this->repository))->createDisposable();
        $promocodeArr = $promocodes->first();

        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertCount(1, $promocodes);

        $this->repository->shouldReceive('byCode')->andReturn($this->repository);
        $this->repository->shouldReceive('first')->andReturn($promocode);

        $promocode = $this->repository->byCode($promocode['code'])->first();
        $user = \App\Tenant\Models\Student::factory()->make();

        $promocode->users()->attach($user->id, [
            'promocode_id' => $promocode->id,
            'used_at' => date('Y-m-d H:i:s'),
        ]);

        $this->assertCount(1, $promocodes);

        $this->repository->shouldReceive('promocodeusableExist')->once()
            ->andReturn(true);

        $checkPromocode = (new Promocodes($this->repository))->check($promocode['code']);

        $this->assertFalse($checkPromocode);
    }
}
