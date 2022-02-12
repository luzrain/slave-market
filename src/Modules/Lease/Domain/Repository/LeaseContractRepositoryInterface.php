<?php declare(strict_types=1);

namespace SlaveMarket\Modules\Lease\Domain\Repository;

use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;
use Symfony\Component\Uid\Ulid;
use DateTimeImmutable;

/**
 * Репозиторий договоров аренды
 */
interface LeaseContractRepositoryInterface
{
    /**
     * Возвращает список договоров аренды для раба, в которых заняты часы из указанного периода
     *
     * @return LeaseContract[]
     */
    public function getForSlave(Ulid $slaveId, DateTimeImmutable $dateFrom, DateTimeImmutable $dateTo): array;
}
