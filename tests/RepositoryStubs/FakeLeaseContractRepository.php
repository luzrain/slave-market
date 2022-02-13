<?php

namespace SlaveMarket\Tests\RepositoryStubs;

use SlaveMarket\Modules\Lease\Domain\Repository\LeaseContractRepositoryInterface;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;

/**
 * Stub репозитория договоров
 */
trait FakeLeaseContractRepository
{
    public function makeFakeLeaseContractRepository(LeaseContract ...$contracts): LeaseContractRepositoryInterface
    {
        $leaseContractRepository = $this->createMock(LeaseContractRepositoryInterface::class);

        $leaseContractRepository->method('getForSlave')
            ->willReturn($contracts);

        return $leaseContractRepository;
    }
}
