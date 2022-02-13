<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange;

use Carbon\Carbon;
use DateTimeImmutable;
use Webmozart\Assert\Assert;

/**
 * Диапазон времени аренды
 */
class LeaseDateTimeRange
{
    /**
     * @param DateTimeImmutable $startTime
     * @param DateTimeImmutable $endTime
     */
    public function __construct(
        private DateTimeImmutable $startTime,
        private DateTimeImmutable $endTime,
    ) {
        Assert::greaterThan($endTime, $startTime);
    }

    /**
     * Получить время начала аренды
     *
     * @return DateTimeImmutable
     */
    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * Получить время окончания аренды
     *
     * @return DateTimeImmutable
     */
    public function getEndTime(): DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * Получить массив арендованных часов для текущего договора
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
     * Получить пересекающиеся часы в из переданного массива часов и массива часов из текущего договора
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
