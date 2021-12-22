<?php
declare(strict_types=1);

namespace SlaveMarket\Master;

/**
 * Репозиторий хозяев
 */
interface MastersRepository
{
    /**
     * Возвращает хозяина по его id
     */
    public function getById(int $id) : Master;
}
