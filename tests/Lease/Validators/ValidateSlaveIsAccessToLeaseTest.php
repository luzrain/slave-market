<?php

namespace SlaveMarket\Tests\Lease\Validators;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Validators\ValidateSlaveIsAccessToLease;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;
use SlaveMarket\Modules\Master\Persistence\Entity\Master;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\Sex;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\SkinColor;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;
use SlaveMarket\Tests\RepositoryStubs\FakeLeaseContractRepository;
use SlaveMarket\Modules\Master\Domain\Logic\Characteristics\Vip;

/**
 * Тестирование работы валидатора ValidateSlaveIsAccessToLease.
 * Проверяет новый договор аренды, чтобы время аренды не перекрывало уже занятое другими договорами время
 */
class ValidateSlaveIsAccessToLeaseTest extends TestCase
{
    // Stub репозитория договоров
    use FakeLeaseContractRepository;

    private Master $master1;
    private Master $master2;
    private Slave $slave;

    protected function setUp(): void
    {
        // Хозяин 1
        $this->master1 = new Master(
            name: 'Господин Боб',
        );

        // Хозяин 2
        $this->master2 = new Master(
            name: 'Уродливый Фред',
        );

        // Раб
        $this->slave = new Slave(
            name: 'Майкл',
            sex: Sex::MALE,
            dob: CarbonImmutable::parse('1990-02-10'),
            weight: 77.3,
            skinColor: SkinColor::GREEN
        );
    }

    /**
     * Договор аренды не может быть оформлен на уже занятое время
     */
    public function testTimeIntersection(): void
    {
        // Договор аренды. Хозяин1 арендовал раба 6 часов
        $leaseContract1 = new LeaseContract(
            master: $this->master1,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-01 06:00:00'),
            ),
        );

        // Договор аренды. Хозяин2 пытается арендовать того же раба на уже занятое время
        $leaseContract2 = new LeaseContract(
            master: $this->master2,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 04:00:00'),
                CarbonImmutable::parse('2022-01-01 10:00:00'),
            ),
        );

        $contractRepository = $this->makeFakeLeaseContractRepository($leaseContract1);
        $validator = new ValidateSlaveIsAccessToLease($contractRepository);

        $this->expectException(LeaseRequestException::class);
        $validator->validate($leaseContract2);
    }

    /**
     * Тестирование приоритетности VIP статусов при аренде на занятое время
     * @dataProvider vipStatusProvider
     */
    public function testTimeIntersectionForVip(?Vip $vip1, ?Vip $vip2, bool $isVip2MorePriority): void
    {
        // Vip статус второго хозяина больше, чем у первого (у первого нет VIP статуса)
        $this->master1->setVipStatus($vip1);
        $this->master2->setVipStatus($vip2);

        // Договор аренды. Хозяин1 арендовал раба 6 часов
        $leaseContract1 = new LeaseContract(
            master: $this->master1,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-01 06:00:00'),
            ),
        );

        // Договор аренды. Хозяин2 пытается арендовать того же раба на уже занятое время
        $leaseContract2 = new LeaseContract(
            master: $this->master2,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 04:00:00'),
                CarbonImmutable::parse('2022-01-01 10:00:00'),
            ),
        );

        $contractRepository = $this->makeFakeLeaseContractRepository($leaseContract1);
        $validator = new ValidateSlaveIsAccessToLease($contractRepository);

        if ($isVip2MorePriority) {
            // no exception
            $this->assertTrue(true);
        } else {
            $this->expectException(LeaseRequestException::class);
        }

        $validator->validate($leaseContract2);
    }

    /**
     * Датапровайдер для тестирования приоритетности мастера с VIP статусом при аренде
     * 1 значение: VIP статус 1 хозяина
     * 2 значение: VIP статус 2 хозяина
     * 3 значение: имеет ли приоритет второй над первым
     *
     * @return array
     */
    public function vipStatusProvider(): array
    {
        return [
            [null, null, false],
            [null, Vip::GOLD, true],
            [Vip::GOLD, null, false],
            [Vip::SILVER, Vip::GOLD, true],
            [Vip::GOLD, Vip::SILVER, false],
            [Vip::GOLD, Vip::GOLD, false],
        ];
    }
}
