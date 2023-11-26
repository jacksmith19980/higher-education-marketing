<?php

namespace Tests\Unit\Promocodes;

use App\Exceptions\AlreadyUsedException;
use App\Promocodes;
use App\Repository\PromocodeRepository;
use Mockery;
use Tests\TestCase;

class RewardableTraitT extends TestCase
{
    public $user;

    protected function setUp(): void
    {
        parent::setUp();

//        $this->user = User::find(1);
        $this->user = \App\Tenant\Models\Student::factory()->make();
        $this->actingAs($this->user);
        $this->repository = Mockery::mock(PromocodeRepository::class);
        $this->repository->shouldReceive('pluck')
            ->andReturn(collect([1, 2, 3]));
        $this->repository->shouldReceive('byCode')
            ->andReturn($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_returns_null_if_could_not_apply_promocode()
    {
        $this->repository->shouldReceive('first')->once()
            ->andReturn(null);

        $applyCode = $this->user->setRepository($this->repository)->applyCode('INVALID-CODE');

        $this->assertNull($applyCode);
    }

    /** @test */
    public function it_returns_null_in_callback_if_could_not_apply_promocode()
    {
        $this->repository->shouldReceive('first')->once()
            ->andReturn(null);

        $this->user->setRepository($this->repository)->applyCode('INVALID-CODE', function ($applyCode) {
            $this->assertNull($applyCode);
        });
    }

    /** @test */
    public function it_throws_exception_if_user_already_applied_to_code()
    {
//        $this->expectException(AlreadyUsedException::class);

        $this->repository->shouldReceive('insert')->once()
            ->andReturn(true);
        $promocodes = (new Promocodes($this->repository))->create();
        $promocodeArr = $promocodes->first();
        $promocode = \App\Tenant\Models\Promocode::factory()->make($promocodeArr);

        $this->assertCount(1, $promocodes);

        $this->repository->shouldReceive('first')
            ->andReturn($promocode);

        $this->user->setRepository($this->repository)->applyCode($promocode['code']);
        $this->user->setRepository($this->repository)->applyCode($promocode['code']);
    }
}
