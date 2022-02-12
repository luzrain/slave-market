<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange;

use Carbon\Carbon;
use DateTimeImmutable;
use Webmozart\Assert\Assert;

/**
 * Диапазон времени аренды.
 */
class LeaseDateTimeRange
{
    public function __construct(
        private DateTimeImmutable $startTime,
        private DateTimeImmutable $endTime,
    ) {
        Assert::greaterThan($endTime, $startTime);
    }

    /**
     * Время начала аренды.
     *
     * @return DateTimeImmutable
     */
    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * Время окончания аренды.
     *
     * @return DateTimeImmutable
     */
    public function getEndTime(): DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * Массив часов, включающий часы аредны для текущего договора.
     *
     * @return LeaseHour[]
     */
    public function getLeaseHours(): array
    {
        $hourCounter = Carbon::instance($this->startTime)->startOfHour();
        $hours = [];

        do {
            $hours[] = new LeaseHour($hourCounter->toImmutable());
            $hourCounter->next('hour');
        } while ($hourCounter <= $this->endTime);

        return $hours;
    }

    /**
     * Возвращает пересекающиеся часы в переданном диапазоне и текущем договоре.
     *
     * @param LeaseHour[] $comparedHours
     * @return LeaseHour[]
     */
    public function getIntersectedHours(array $comparedHours): array
    {
        // Функция для вычисления пересечения массивов часов
        $comparator = fn (LeaseHour $a, LeaseHour $b): int => $a->getDateTime() <=> $b->getDateTime();

        // Поиск пересекающихся значений
        $intersections = \array_uintersect($this->getLeaseHours(), $comparedHours, $comparator);

        return \array_values($intersections);
    }
}
