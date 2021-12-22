<?php
declare(strict_types=1);

namespace SlaveMarket\Lease;

use SlaveMarket\Master\MastersRepository;
use SlaveMarket\Slave\SlavesRepository;

/**
 * Операция "Арендовать раба"
 */
class LeaseOperation
{
    public function __construct(
        private LeaseContractRepository $contractsRepo,
        private MastersRepository $mastersRepo,
        private SlavesRepository $slavesRepo,
    ) {
    }

    /**
     * Выполнить операцию
     *
     * @param LeaseRequest $request
     * @return LeaseResponse
     */
    public function run(LeaseRequest $request): LeaseResponse
    {
        // Your code goes here :-)
    }
}
