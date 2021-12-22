<?php
declare(strict_types=1);

namespace SlaveMarket\Lease;

/**
 * Запрос на аренду раба
 */
class LeaseRequest
{
    public function __construct(
        /** @var int id хозяина */
        public readonly int $masterId,
        /** @var int id раба */
        public readonly int $slaveId,
        /** @var string Время начала работ Y-m-d H:i:s */
        public readonly string $timeFrom,
        /** @var string Время окончания работ Y-m-d H:i:s */
        public readonly string $timeTill,
    ) {
    }
}
