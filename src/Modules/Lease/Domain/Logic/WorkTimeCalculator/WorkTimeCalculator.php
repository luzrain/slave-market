<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\WorkTimeCalculator;

use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;

/**
 * Калькулятр стоимости аренды и рабочих часов
 */
class WorkTimeCalculator
{
    // Количество оплачиваемых часов при аренде на полный день
    private const FULLDAY_PAID_HOURS = 16;

    /**
     * @param LeaseDateTimeRange $dateTimeRange
     * @param float $pricePerHour
     */
    public function __construct(
        private LeaseDateTimeRange $dateTimeRange,
        private float $pricePerHour,
    ) {
    }

    /**
     * Получить количество часов аренды
     *
     * @return int
     */
    public function getHours(): int
    {
        return \count($this->dateTimeRange->getLeaseHours());
    }

    /**
     * Получить количество оплачиваемых часов аренды
     *
     * @return int
     */
    public function getPaidHours(): int
    {
        $map = [];
        $hoursCounter = 0;

        foreach ($this->dateTimeRange->getLeaseHours() as $hour) {
            $map[$hour->getDate()][] = $hour;
        }

        foreach ($map as $hours) {
            $count = \count($hours);
            $hoursCounter += ($count === 24) ? self::FULLDAY_PAID_HOURS : $count;
        }

        return $hoursCounter;
    }

    /**
     * Получить стоимость аренды
     *
     * @return float
     */
    public function getPrice(): float
    {
        return \round($this->getPaidHours() * $this->pricePerHour, 2);
    }
}
