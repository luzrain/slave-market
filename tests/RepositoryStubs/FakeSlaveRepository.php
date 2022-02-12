<?php

namespace SlaveMarket\Tests\RepositoryStubs;

use SlaveMarket\Modules\Slave\Domain\Repository\SlavesRepositoryInterface;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;

trait FakeSlaveRepository
{
    /**
     * Stub репозитория рабов
     */
    public function makeFakeSlaveRepository(Slave ...$slaves): SlavesRepositoryInterface
    {
        $slavesRepository = $this->createMock(SlavesRepositoryInterface::class);
        $map = [];

        foreach ($slaves as $slave) {
            $map[] = [$slave->getId(), $slave];
        }

        $slavesRepository
            ->method('getById')
            ->willReturnMap($map);

        return $slavesRepository;
    }
}
