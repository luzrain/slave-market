<?php

declare(strict_types=1);

namespace SlaveMarket\Modules\Lease\Domain\Command;

use Symfony\Component\Uid\Ulid;
use DateTimeImmutable;

/**
 * Запрос на аренду раба.
 */
class LeaseRequestCommand
{
    public function __construct(
        /** @var Ulid id хозяина */
        public readonly Ulid $masterId,

        /** @var Ulid id раба */
        public readonly Ulid $slaveId,

        /** @var DateTimeImmutable Время начала работ */
        public readonly DateTimeImmutable $dateFrom,

        /** @var DateTimeImmutable Время окончания работ */
        public readonly DateTimeImmutable $dateTo,
    ) {
    }
}
