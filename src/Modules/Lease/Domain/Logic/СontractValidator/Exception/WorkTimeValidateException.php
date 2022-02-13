<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Exception;

use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Slave\Persistence\Entity\Slave;

/**
 * Исключение валидатора рабочего времени
 */
class WorkTimeValidateException extends LeaseRequestException
{
    private const TEMPLATE = 'Ошибка. %s у раба #%s "%s" свободно %s часов';

    /**
     * @param string $date
     * @param Slave $slave
     * @param int $freeHours
     */
    public function __construct(string $date, Slave $slave, int $freeHours)
    {
        $error = sprintf(self::TEMPLATE, $date, $slave->getId()->toBase32(), $slave->getName(), $freeHours);
        parent::__construct($error);
    }
}
