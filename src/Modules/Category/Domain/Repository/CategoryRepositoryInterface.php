<?php

declare(strict_types=1);

namespace SlaveMarket\Modules\Category\Domain\Repository;

use SlaveMarket\Modules\Category\Persistence\Entity\Category;
use Symfony\Component\Uid\Ulid;

/**
 * Репозиторий категорий
 */
interface CategoryRepositoryInterface
{
    /**
     * Возвращает категорию по id
     */
    public function getById(Ulid $id): Category;
}
