<?php

namespace SlaveMarket\Tests\RepositoryStubs;

use SlaveMarket\Modules\Lease\Domain\Repository\LeaseContractRepositoryInterface;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;

trait FakeLeaseContractRepository
{
    /**
     * Stub репозитория договоров
     */
    public function makeFakeLeaseContractRepository(LeaseContract ...$contracts): LeaseContractRepositoryInterface
    {
        $leaseContractRepository = $this->createMock(LeaseContractRepositoryInterface::class);

        $leaseContractRepository->method('getForSlave')
            ->willReturn($contracts);

        return $leaseContractRepository;
    }
}
