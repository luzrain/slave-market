<?php
declare(strict_types=1);

namespace SlaveMarket\Slave;

/**
 * Репозиторий рабов
 */
interface SlavesRepository
{
    /**
     * Возвращает раба по его id
     */
    public function getById(int $id): Slave;
}
