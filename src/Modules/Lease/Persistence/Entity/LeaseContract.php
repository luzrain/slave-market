<?php declare(strict_types=1);

namespace SlaveMarket\Modules\Lease\Persistence\Entity;

use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
use SlaveMarket\Modules\Lease\Domain\Logic\WorkTimeCalculator\WorkTimeCalculator;
use SlaveMarket\Modules\Master\Persistence\Entity\Master;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;
use Symfony\Component\Uid\Ulid;

/**
 * Договор аренды
 */
final class LeaseContract
{
    /**
     * Id.
     */
    private Ulid $id;

    /**
     * Хозяин, арендовавший раба.
     */
    private Master $master;

    /**
     * Раб.
     */
    private Slave $slave;

    /**
     * Диапазон времени аренды аренды
     */
    private LeaseDateTimeRange $dateTimeRange;

    /**
     * Количество часов аренды
     */
    private int $hours;

    /**
     * Стоимость аренды
     */
    private float $price;

    /**
     * @param Master $master
     * @param Slave $slave
     * @param LeaseDateTimeRange $dateTimeRange
     */
    public function __construct(
        Master $master,
        Slave $slave,
        LeaseDateTimeRange $dateTimeRange,
    ) {
        $workTimeCalculator = new WorkTimeCalculator($dateTimeRange, $slave->getPricePerHour());
        $this->id = new Ulid();
        $this->master = $master;
        $this->slave = $slave;
        $this->dateTimeRange = $dateTimeRange;
        $this->hours = $workTimeCalculator->getHours();
        $this->price = $workTimeCalculator->getPrice();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getMaster(): Master
    {
        return $this->master;
    }

    public function getSlave(): Slave
    {
        return $this->slave;
    }

    public function getDateTimeRange(): LeaseDateTimeRange
    {
        return $this->dateTimeRange;
    }

    public function getHours(): int
    {
        return $this->hours;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
