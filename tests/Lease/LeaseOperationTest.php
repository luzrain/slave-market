<?php

namespace SlaveMarket\Lease;

use PHPUnit\Framework\TestCase;
use SlaveMarket\Master\Master;
use SlaveMarket\Master\MastersRepository;
use SlaveMarket\Slave\Slave;
use SlaveMarket\Slave\SlavesRepository;

/**
 * Тестирование операции аренды раба
 */
class LeaseOperationTest extends TestCase
{
    /**
     * Если раб занят, то арендовать его не получится
     */
    public function test_run_slaveIsBusy(): void
    {
        // Arrange
        {
            // Хозяева
            $master1 = new Master(1, 'Господин Боб');
            $master2 = new Master(2, 'сэр Вонючка');
            $masterRepository = $this->makeFakeMasterRepository($master1, $master2);

            // Раб
            $slave1 = new Slave(1, 'Уродливый Фред', 20);
            $slaveRepository = $this->makeFakeSlaveRepository($slave1);

            // Договор аренды. 1ый хозяин арендовал раба
            $leaseContract1 = new LeaseContract($master1, $slave1, 80, [
                new LeaseHour('2017-01-01 00'),
                new LeaseHour('2017-01-01 01'),
                new LeaseHour('2017-01-01 02'),
                new LeaseHour('2017-01-01 03'),
            ]);

            $contractRepository = $this->makeFakeLeaseContractRepository([$leaseContract1], $slave1, '2017-01-01', '2017-01-01');

            // Запрос на новую аренду. 2ой хозяин выбрал занятое время
            $leaseRequest = new LeaseRequest(
                $master2->getId(),
                $slave1->getId(),
                '2017-01-01 01:30:00',
                '2017-01-01 02:01:00',
            );

            // Операция аренды
            $leaseOperation = new LeaseOperation($contractRepository, $masterRepository, $slaveRepository);
        }

        // Act
        $response = $leaseOperation->run($leaseRequest);

        // Assert
        $expectedErrors = ['Ошибка. Раб #1 "Уродливый Фред" занят. Занятые часы: "2017-01-01 01", "2017-01-01 02"'];

        self::assertSame($expectedErrors, $response->getErrors());
        self::assertNull($response->getLeaseContract());
    }

    /**
     * Если раб бездельничает, то его легко можно арендовать
     */
    public function test_run_slaveSuccessfullyLeased(): void
    {
        // Arrange
        {
            // Хозяева
            $master1 = new Master(1, 'Господин Боб');
            $masterRepository = $this->makeFakeMasterRepository($master1);

            // Раб
            $slave1 = new Slave(1, 'Уродливый Фред', 20);
            $slaveRepository = $this->makeFakeSlaveRepository($slave1);

            $contractRepository = $this->makeFakeLeaseContractRepository([], $slave1, '2017-01-01', '2017-01-01');

            // Запрос на новую аренду
            $leaseRequest = new LeaseRequest(
                $master1->getId(),
                $slave1->getId(),
                '2017-01-01 01:30:00',
                '2017-01-01 02:01:00',
            );

            // Операция аренды
            $leaseOperation = new LeaseOperation($contractRepository, $masterRepository, $slaveRepository);
        }

        // Act
        $response = $leaseOperation->run($leaseRequest);

        // Assert
        self::assertEmpty($response->getErrors());
        self::assertInstanceOf(LeaseContract::class, $response->getLeaseContract());
        self::assertEquals(40, $response->getLeaseContract()->price);
    }

    /**
     * Stub репозитория договоров
     *
     * @param LeaseContract[] $contracts
     */
    private function makeFakeLeaseContractRepository(
        array $contracts,
        Slave $slave,
        string $dateFrom,
        string $dateTill,
    ): LeaseContractRepository
    {
        $leaseContractRepository = $this->createPartialMock(LeaseContractRepository::class, ['getForSlave']);

        $leaseContractRepository->method('getForSlave')
            ->with($slave->getId(), $dateFrom, $dateTill)
            ->willReturn($contracts);

        return $leaseContractRepository;
    }

    /**
     * Stub репозитория хозяев
     */
    private function makeFakeMasterRepository(Master ...$masters): MastersRepository
    {
        $mastersRepository = $this->createPartialMock(MastersRepository::class, ['getById']);

        foreach ($masters as $master) {
            $mastersRepository->method('getById')
                ->with($master->getId())
                ->willReturn($master);
        }

        return $mastersRepository;
    }

    /**
     * Stub репозитория рабов
     */
    private function makeFakeSlaveRepository(Slave ...$slaves): SlavesRepository
    {
        $slavesRepository = $this->createPartialMock(SlavesRepository::class, ['getById']);

        foreach ($slaves as $slave) {
            $slavesRepository->method('getById')
                ->with($slave->getId())
                ->willReturn($slave);
        }

        return $slavesRepository;
    }
}
