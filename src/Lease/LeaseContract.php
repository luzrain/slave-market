<?php
declare(strict_types=1);

namespace SlaveMarket\Lease;

use SlaveMarket\Master\Master;
use SlaveMarket\Slave\Slave;

/**
 * Договор аренды
 */
class LeaseContract
{
    public function __construct(
        public readonly Master $master,
        public readonly Slave $slave,
        public readonly float $price,
        public readonly array $leasedHours,
    ) {
    }
}
