<?php declare(strict_types=1);

namespace SlaveMarket\Modules\Master\Domain\Repository;

use SlaveMarket\Modules\Master\Persistence\Entity\Master;
use Symfony\Component\Uid\Ulid;

/**
 * Репозиторий хозяев
 */
interface MasterRepositoryInterface
{
    /**
     * Возвращает хозяина по его id
     */
    public function getById(Ulid $id): Master;
}
