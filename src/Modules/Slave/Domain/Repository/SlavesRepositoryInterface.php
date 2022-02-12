<?php declare(strict_types=1);

namespace SlaveMarket\Modules\Slave\Domain\Repository;

use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;
use Symfony\Component\Uid\Ulid;

/**
 * Репозиторий рабов
 */
interface SlavesRepositoryInterface
{
    /**
     * Возвращает раба по его id
     */
    public function getById(Ulid $id): Slave;
}
