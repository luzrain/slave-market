<?php

namespace SlaveMarket\Tests\Lease\Validators;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Validators\ValidateSlaveWorkTime;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;
use SlaveMarket\Modules\Master\Persistence\Entity\Master;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\Sex;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\SkinColor;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;
use SlaveMarket\Tests\RepositoryStubs\FakeLeaseContractRepository;

/**
 * Тестирование работы валидатора ValidateSlaveWorkTimeTest.
 * Проверяет чтобы итоговое время раба не превышало 16 часов в сутки
 */
class ValidateSlaveWorkTimeTest extends TestCase
{
    // Stub репозитория договоров
    use FakeLeaseContractRepository;

    private Master $master;
    private Slave $slave;

    protected function setUp(): void
    {
        // Хозяин
        $this->master = new Master(
            name: 'Господин Боб',
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
     * Договор аренды не может быть оформлен если время работы раба превысит 16 часов в день
     */
    public function testWorkTimeExceeding(): void
    {
        // Договор аренды. Хозяин арендовал раба 12 часов
        $leaseContract1 = new LeaseContract(
            master: $this->master,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-01 11:00:00'),
            ),
        );

        // Договор аренды. Хозяин пытается арендовать того же раба еще на 6 часов
        $leaseContract2 = new LeaseContract(
            master: $this->master,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 15:00:00'),
                CarbonImmutable::parse('2022-01-01 20:00:00'),
            ),
        );

        $contractRepository = $this->makeFakeLeaseContractRepository($leaseContract1);
        $validator = new ValidateSlaveWorkTime($contractRepository);

        $this->expectException(LeaseRequestException::class);
        $validator->validate($leaseContract2);
    }

    /**
     * Договор аренды не может быть оформлен если раб арендуется на несколько рабочих дней и у неполных дней превышен лимит в 16 часов
     */
    public function testWorkFullDaysInDataRangeAndTimeExceeding(): void
    {
        // Договор аренды. Хозяин арендовал раба на 5 полных дней + 21 час неполного дня
        $leaseContract = new LeaseContract(
            master: $this->master,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-06 20:00:00'),
            ),
        );

        $contractRepository = $this->makeFakeLeaseContractRepository();
        $validator = new ValidateSlaveWorkTime($contractRepository);

        $this->expectException(LeaseRequestException::class);
        $validator->validate($leaseContract);
    }

    /**
     * Договор аренды может быть оформлен если раб арендуется на несколько рабочих дней и у неполных дней не превышен лимит в 16 часов
     */
    public function testWorkFullDaysInDataRangeAndTimeNotExceeding(): void
    {
        // Договор аренды. Хозяин арендовал раба на 5 полных дней + 10 часов неполного дня
        $leaseContract = new LeaseContract(
            master: $this->master,
            slave: $this->slave,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-06 09:00:00'),
            ),
        );

        $contractRepository = $this->makeFakeLeaseContractRepository();
        $validator = new ValidateSlaveWorkTime($contractRepository);
        $validator->validate($leaseContract);

        // no exceptions
        $this->assertTrue(true);
    }
}
