<?php

namespace SlaveMarket\Tests\Lease\WorkTimeCalculator;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
use SlaveMarket\Modules\Lease\Domain\Logic\WorkTimeCalculator\WorkTimeCalculator;

/**
 * Тестирование корректности вычисления стоимости аренды и количества рабочих часов
 */
class WorkTimeCalculatorTest extends TestCase
{
    /**
     * Проверка вычисляемой стоимости аренды для неполного дня
     */
    public function testPartialDay(): void
    {
        $workTimeCalculator = new WorkTimeCalculator(
            //Стоимость аренды - 50 в час
            pricePerHour: 50,

            //Аренда на 9 часов
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-01 08:00:00'),
            ),
        );

        $this->assertEquals(9 * 50, $workTimeCalculator->getPrice());
        $this->assertEquals(9, $workTimeCalculator->getPaidHours());
        $this->assertEquals(9, $workTimeCalculator->getHours());
    }

    /**
     * Проверка вычисляемой стоимости аренды для полных дней
     * Оплачиваемые часы считаем так: 3 полных дня считаем по 16 часов + 9 часов из неполного дня = 57 оплачиваемых часов
     */
    public function testFullDay(): void
    {
        $workTimeCalculator = new WorkTimeCalculator(
            //Стоимость аренды - 50 в час
            pricePerHour: 50,

            //Аренда на 3 полных дня и 9 часов
            dateTimeRange: new LeaseDateTimeRange(
                CarbonImmutable::parse('2022-01-01 00:00:00'),
                CarbonImmutable::parse('2022-01-04 08:00:00'),
            ),
        );

        $this->assertEquals(57 * 50, $workTimeCalculator->getPrice());
        $this->assertEquals(57, $workTimeCalculator->getPaidHours());
        $this->assertEquals(81, $workTimeCalculator->getHours());
    }
}
