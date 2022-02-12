<?php

namespace SlaveMarket\Tests\Lease\Api;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Modules\Lease\Api\LeaseCreateHandler;
use SlaveMarket\Modules\Lease\Domain\Command\LeaseRequestCommand;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\СontractValidatorFactory;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;
use SlaveMarket\Modules\Master\Persistence\Entity\Master;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\Sex;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\SkinColor;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;
use SlaveMarket\Tests\RepositoryStubs\FakeLeaseContractRepository;
use SlaveMarket\Tests\RepositoryStubs\FakeMasterRepository;
use SlaveMarket\Tests\RepositoryStubs\FakeSlaveRepository;

/**
 * Тестирование операции аренды раба при превышении допустимого времени аренды в 16 часов в сутки
 */
class LeaseCreateExceedingHoursTest extends TestCase
{
    // Stub репозитория хозяев
    use FakeMasterRepository;

    // Stub репозитория рабов
    use FakeSlaveRepository;

    // Stub репозитория договоров
    use FakeLeaseContractRepository;

    private Master $master1;
    private Master $master2;
    private Slave $slave1;

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
        $this->slave1 = new Slave(
            name: 'Майкл',
            sex: Sex::MALE,
            dob: CarbonImmutable::parse('1990-02-10'),
            weight: 77.3,
            skinColor: SkinColor::GREEN
        );
    }

    /**
     * LeaseCreateHandler
     */
    private function getLeaseCreateHandler(LeaseContract ...$leaseContracts): LeaseCreateHandler
    {
        $masterRepository = $this->makeFakeMasterRepository($this->master1, $this->master2);
        $slaveRepository = $this->makeFakeSlaveRepository($this->slave1);
        $contractRepository = $this->makeFakeLeaseContractRepository(...$leaseContracts);
        $contractValidatorFactory = new СontractValidatorFactory($contractRepository);

        return new LeaseCreateHandler($masterRepository, $slaveRepository, $contractValidatorFactory);
    }

    /**
     * Нельзя арендовать раба на время, больше, чем 16 часов в сутки
     */
    public function testTimeGreaterThenMaximum(): void
    {
        // Договор аренды. 1ый хозяин арендовал раба на 13 часов (часы: 00 - 12)
        $leaseContract1 = new LeaseContract(
            master: $this->master1,
            slave: $this->slave1,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-01 12:00:00'),
            ),
        );

        // Запрос на новую аренду. 2ой хозяин выбрал 6 часов аренды на этот же день
        $leaseRequestCommand = new LeaseRequestCommand(
            masterId: $this->master2->getId(),
            slaveId: $this->slave1->getId(),
            dateFrom: CarbonImmutable::parse('2022-01-01 15:00:00'),
            dateTo: CarbonImmutable::parse('2022-01-01 20:00:00'),
        );

        $leaseCreateHandler = $this->getLeaseCreateHandler($leaseContract1);

        $this->expectException(LeaseRequestException::class);
        $this->expectDeprecationMessageMatches('/^Ошибка\. 2022-01-01 у раба #\w{26} "Майкл" свободно 3 часов$/');

        $leaseCreateHandler->handle($leaseRequestCommand);
    }

    /**
     * Можно арендовать раба на 16 часов в сутки
     */
    public function testMaximumTime(): void
    {
        // Договор аренды. 1ый хозяин арендовал раба на 13 часов (часы: 00 - 12)
        $leaseContract1 = new LeaseContract(
            master: $this->master1,
            slave: $this->slave1,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-01 12:00:00'),
            ),
        );

        // Запрос на новую аренду. 2ой хозяин выбрал 3 часов аренды на этот же день
        $leaseRequestCommand = new LeaseRequestCommand(
            masterId: $this->master2->getId(),
            slaveId: $this->slave1->getId(),
            dateFrom: CarbonImmutable::parse('2022-01-01 15:00:00'),
            dateTo: CarbonImmutable::parse('2022-01-01 17:00:00'),
        );

        $leaseCreateHandler = $this->getLeaseCreateHandler($leaseContract1);
        $leaseContract = $leaseCreateHandler->handle($leaseRequestCommand);

        $this->assertInstanceOf(LeaseContract::class, $leaseContract);
    }

    /**
     * Правило 16 часов не учитывается для полных дней, но учитывается для неполных
     */
    public function testMultipleDays(): void
    {
        // Договор аренды. 1ый хозяин арендовал раба на 10 часов (часы: 00 - 09)
        $leaseContract1 = new LeaseContract(
            master: $this->master1,
            slave: $this->slave1,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-01 09:00:00'),
            ),
        );

        // Запрос на новую аренду. 2ой хозяин арендовал раба на 69 часов (1 час в этот же день + 2 полных дня + 20 часов)
        $leaseRequestCommand = new LeaseRequestCommand(
            masterId: $this->master2->getId(),
            slaveId: $this->slave1->getId(),
            dateFrom: CarbonImmutable::parse('2022-01-01 23:00:00'),
            dateTo: CarbonImmutable::parse('2022-01-04 20:00:00'),
        );

        $leaseCreateHandler = $this->getLeaseCreateHandler($leaseContract1);

        $this->expectException(LeaseRequestException::class);
        $this->expectDeprecationMessageMatches('/^Ошибка\. 2022-01-04 у раба #\w{26} "Майкл" свободно 16 часов$/');

        $leaseCreateHandler->handle($leaseRequestCommand);
    }
}
