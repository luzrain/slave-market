<?php

namespace SlaveMarket\Tests\Lease\Api;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Modules\Lease\Api\LeaseCreateHandler;
use SlaveMarket\Modules\Lease\Domain\Command\LeaseRequestCommand;
use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\СontractValidatorFactory;
use SlaveMarket\Modules\Master\Persistence\Entity\Master;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\Sex;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\SkinColor;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;
use SlaveMarket\Tests\RepositoryStubs\FakeLeaseContractRepository;
use SlaveMarket\Tests\RepositoryStubs\FakeMasterRepository;
use SlaveMarket\Tests\RepositoryStubs\FakeSlaveRepository;

/**
 * Тестирование списания золота со счета хозяина
 */
class MasterChargeTest extends TestCase
{
    // Stub репозитория хозяев
    use FakeMasterRepository;

    // Stub репозитория рабов
    use FakeSlaveRepository;

    // Stub репозитория договоров
    use FakeLeaseContractRepository;

    /**
     * У хозяина со счета списывается сумма аренды
     */
    public function testGoldChargeFromMasterBalance(): void
    {
        // Хозяин. 1000 золота на счету
        $master = new Master(
            name: 'Господин Боб',
            gold: 1000,
        );

        // Раб. Стоимость аренды 25 золота в час
        $slave = new Slave(
            name: 'Майкл',
            sex: Sex::MALE,
            skinColor: SkinColor::GREEN,
            dob: CarbonImmutable::parse('1990-02-10'),
            weight: 77.3,
            pricePerHour: 25,
        );

        // Договор аренды. Хозяин арендовал раба на 8 часов
        $leaseRequestCommand = new LeaseRequestCommand(
            masterId: $master->getId(),
            slaveId: $slave->getId(),
            dateFrom: CarbonImmutable::parse('2022-01-01 12:00:00'),
            dateTo: CarbonImmutable::parse('2022-01-01 19:00:00'),
        );

        $masterRepository = $this->makeFakeMasterRepository($master);
        $slaveRepository = $this->makeFakeSlaveRepository($slave);
        $contractRepository = $this->makeFakeLeaseContractRepository();
        $contractValidatorFactory = new СontractValidatorFactory($contractRepository);
        $leaseCreateHandler = new LeaseCreateHandler($masterRepository, $slaveRepository, $contractValidatorFactory);

        $leaseCreateHandler->handle($leaseRequestCommand);

        // 25 золота * 8 часов = 200 золота итоговая стоимость
        $this->assertEquals(800, $master->getGold());
    }
}
