<?php

namespace SlaveMarket\Tests\RepositoryStubs;

use SlaveMarket\Modules\Master\Domain\Repository\MasterRepositoryInterface;
use SlaveMarket\Modules\Master\Persistence\Entity\Master;

trait FakeMasterRepository
{
    /**
     * Stub репозитория хозяев
     */
    public function makeFakeMasterRepository(Master ...$masters): MasterRepositoryInterface
    {
        $mastersRepository = $this->createMock(MasterRepositoryInterface::class);
        $map = [];

        foreach ($masters as $master) {
            $map[] = [$master->getId(), $master];
        }

        $mastersRepository
            ->method('getById')
            ->willReturnMap($map);

        return $mastersRepository;
    }
}
