<?php

namespace SlaveMarket\Tests\Lease\Api;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Modules\Lease\Api\LeaseCreateHandler;
use SlaveMarket\Modules\Lease\Domain\Command\LeaseRequestCommand;
use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
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
 * Тестирование операции аренды раба при перекрытии времени аренды с другими существующими договорами
 */
class LeaseCreateBusyTest extends TestCase
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
     * Если раб занят, то арендовать его не получится
     */
    public function testSlaveIsBusy(): void
    {
        // Договор аренды. 1ый хозяин арендовал раба (часы: 10, 11, 12, 13, 14)
        $leaseContract1 = new LeaseContract(
            master: $this->master1,
            slave: $this->slave1,
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 10:20:00'),
                CarbonImmutable::parse('2022-01-01 14:00:00'),
            ),
        );

        $masterRepository = $this->makeFakeMasterRepository($this->master1, $this->master2);
        $slaveRepository = $this->makeFakeSlaveRepository($this->slave1);
        $contractRepository = $this->makeFakeLeaseContractRepository($leaseContract1);
        $contractValidatorFactory = new СontractValidatorFactory($contractRepository);
        $leaseCreateHandler = new LeaseCreateHandler($masterRepository, $slaveRepository, $contractValidatorFactory);

        // Запрос на новую аренду. 2ой хозяин выбрал занятое время. (пересечение: 13, 14)
        $leaseRequestCommand = new LeaseRequestCommand(
            masterId: $this->master2->getId(),
            slaveId: $this->slave1->getId(),
            dateFrom: CarbonImmutable::parse('2022-01-01 13:00:00'),
            dateTo: CarbonImmutable::parse('2022-01-01 16:00:00'),
        );

        $this->expectException(LeaseRequestException::class);
        $this->expectDeprecationMessageMatches('/^Ошибка\. Раб #\w{26} "Майкл" занят\. Занятые часы: "2022-01-01 13", "2022-01-01 14"$/');

        $leaseCreateHandler->handle($leaseRequestCommand);
    }

    /**
     * Если раб бездельничает, то его легко можно арендовать
     */
    public function testSlaveSuccessfullyLeased(): void
    {
        $masterRepository = $this->makeFakeMasterRepository($this->master1);
        $slaveRepository = $this->makeFakeSlaveRepository($this->slave1);
        $contractRepository = $this->makeFakeLeaseContractRepository();
        $contractValidatorFactory = new СontractValidatorFactory($contractRepository);
        $leaseCreateHandler = new LeaseCreateHandler($masterRepository, $slaveRepository, $contractValidatorFactory);

        // Запрос на новую аренду. выбрано свободное время.
        $leaseRequestCommand = new LeaseRequestCommand(
            masterId: $this->master1->getId(),
            slaveId: $this->slave1->getId(),
            dateFrom: CarbonImmutable::parse('2022-01-01 13:00:00'),
            dateTo: CarbonImmutable::parse('2022-01-01 16:00:00'),
        );

        $leaseContract = $leaseCreateHandler->handle($leaseRequestCommand);

        $this->assertInstanceOf(LeaseContract::class, $leaseContract);
    }
}
