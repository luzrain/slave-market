<?php

declare(strict_types=1);

namespace SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange;

use DateTimeImmutable;

/**
 * Арендованный час
 */
class LeaseHour
{
    public function __construct(
        private DateTimeImmutable $dateTime
    ) {
    }

    /**
     * Возвращает строку, представляющую час
     */
    public function getDateString(): string
    {
        return $this->dateTime->format('Y-m-d H');
    }

    /**
     * Возвращает объект даты
     */
    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * Возвращает дату аренды
     */
    public function getDate(): string
    {
        return $this->dateTime->format('Y-m-d');
    }

    /**
     * Возвращает час аренды
     *
     * @return string
     */
    public function getHour(): string
    {
        return $this->dateTime->format('H');
    }
}
