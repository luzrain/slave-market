<?php
declare(strict_types=1);

namespace SlaveMarket\Lease;

/**
 * Репозиторий договоров аренды
 */
interface LeaseContractRepository
{
    /**
     * Возвращает список договоров аренды для раба, в которых заняты часы из указанного периода
     *
     * @return LeaseContract[]
     */
    public function getForSlave(int $slaveId, string $dateFrom, string $dateTo) : array;
}
