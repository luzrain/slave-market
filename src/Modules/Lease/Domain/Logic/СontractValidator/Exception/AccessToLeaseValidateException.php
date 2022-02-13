<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Exception;

use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseHour;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;

/**
 * Исключение валидатора проверки доступных часов
 */
class AccessToLeaseValidateException extends LeaseRequestException
{
    private const TEMPLATE = 'Ошибка. Раб #%s "%s" занят. Занятые часы: %s';

    /**
     * @param Slave $slave
     * @param LeaseHour[] $intersectedHours
     */
    public function __construct(Slave $slave, array $intersectedHours)
    {
        $getDateString = fn (LeaseHour $s): string => '"' . $s->getDateString() . '"';
        $intersections = array_map($getDateString, $intersectedHours);
        $error = sprintf(self::TEMPLATE, $slave->getId()->toBase32(), $slave->getName(), implode(', ', $intersections));
        parent::__construct($error);
    }
}
