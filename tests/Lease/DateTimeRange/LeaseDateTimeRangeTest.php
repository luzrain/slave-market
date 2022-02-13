<?php

namespace SlaveMarket\Tests\Lease\DateTimeRange;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseHour;

/**
 * Тестирование работы объекта диапазона времени аренды LeaseDateTimeRange
 */
class LeaseDateTimeRangeTest extends TestCase
{
    /**
     * Нельзя создать диапазон аренды, если время окончания аренды меньше времени старта аренды
     */
    public function testEndTimeLessThenStartTime(): void
    {
        $start = CarbonImmutable::parse('2022-01-01 13:00:00');
        $end = CarbonImmutable::parse('2022-01-01 11:00:00');

        $this->expectException(InvalidArgumentException::class);
        new LeaseDateTimeRange($start, $end);
    }

    /**
     * Нельзя создать диапазон аренды, если время окончания аренды равно времени старта аренды
     */
    public function testEndTimeEqualStartTime(): void
    {
        $start = CarbonImmutable::parse('2022-01-01 13:00:00');
        $end = CarbonImmutable::parse('2022-01-01 13:00:00');

        $this->expectException(InvalidArgumentException::class);
        new LeaseDateTimeRange($start, $end);
    }

    /**
     * Можно создать диапазон аренды, если время окончания аренды больше времени старта аренды
     */
    public function testEndTimeGreaterThanStartTime(): void
    {
        $start = CarbonImmutable::parse('2022-01-01 13:00:00');
        $end = CarbonImmutable::parse('2022-01-01 14:00:00');

        $range = new LeaseDateTimeRange($start, $end);
        $this->assertInstanceOf(LeaseDateTimeRange::class, $range);
    }

    /**
     * Для диапазона генерируется верный массив объектов LeaseHour
     */
    public function testLeaseHours(): void
    {
        $start = CarbonImmutable::parse('2022-01-01 13:15:00');
        $end = CarbonImmutable::parse('2022-01-01 16:30:00');
        $range = new LeaseDateTimeRange($start, $end);

        $expectedLeaseHour = [
            new LeaseHour(CarbonImmutable::parse('2022-01-01 13:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 14:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 15:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 16:00:00')),
        ];

        $this->assertEquals($expectedLeaseHour, $range->getLeaseHours());
    }

    /**
     * Для диапазона вычисляется верный набор пересекающихся часов
     */
    public function testIntersectedHours(): void
    {
        // Часы диапазона: 13, 14, 15, 16, 17, 18
        $start = CarbonImmutable::parse('2022-01-01 13:15:00');
        $end = CarbonImmutable::parse('2022-01-01 18:30:00');
        $range = new LeaseDateTimeRange($start, $end);

        // Массив часов, частично перекрывающий наш диапазон
        $intersection = [
            new LeaseHour(CarbonImmutable::parse('2022-01-01 16:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 17:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 18:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 19:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 20:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 21:00:00')),
        ];

        // Массив пересекающихся часов
        $expectedIntersection = [
            new LeaseHour(CarbonImmutable::parse('2022-01-01 16:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 17:00:00')),
            new LeaseHour(CarbonImmutable::parse('2022-01-01 18:00:00')),
        ];

        $this->assertEquals($expectedIntersection, $range->getIntersectedHours($intersection));
    }
}
