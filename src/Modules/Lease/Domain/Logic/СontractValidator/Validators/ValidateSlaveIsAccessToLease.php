<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Validators;

use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Exception\AccessToLeaseValidateException;
use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\Validator;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;
use SlaveMarket\Modules\Master\Persistence\Entity\Master;

/**
 * Проверка часов в договоре на доступность
 */
class ValidateSlaveIsAccessToLease extends Validator
{
    /**
     * @param LeaseContract $leaseContract
     * @return void
     * @throws AccessToLeaseValidateException
     */
    public function validate(LeaseContract $leaseContract): void
    {
        $master = $leaseContract->getMaster();
        $slave = $leaseContract->getSlave();
        $dateTimeRange = $leaseContract->getDateTimeRange();
        $intersectedHours = [];

        // Поиск активных договоров аренды за указанный диапазон времени
        $contracts = $this->contractRepository->getForSlave($slave->getId(), $dateTimeRange->getStartTime(), $dateTimeRange->getEndTime());

        // Цикл по всем найденным договорам, чтобы найти пересекающиеся часы
        foreach ($contracts as $intersectedContract) {
            $interceptionMaster = $intersectedContract->getMaster();
            $interceptionDateTimeRange = $intersectedContract->getDateTimeRange();

            // Если VIP статус хозяина больше, чем статус хозяина, уже занявшего диапазон, игнорируем дальнейшую проверку
            if ($this->isVipStatusLagrer($master, $interceptionMaster)) {
                continue;
            }

            // Поиск пересекающихся часов
            $intersectedHours += $interceptionDateTimeRange->getIntersectedHours($dateTimeRange->getLeaseHours());
        }

        // Если нашлись пересекающиеся часы - кидаем исключение
        if (!empty($intersectedHours)) {
            throw new AccessToLeaseValidateException($slave, $intersectedHours);
        }
    }

    /**
     * Является ли VIP статус $master1 большего уровня, чем $master1.
     *
     * @param Master $master1
     * @param Master $master2
     * @return bool
     */
    private function isVipStatusLagrer(Master $master1, Master $master2): bool
    {
        $status1 = $master1->getVipStatus()?->value;
        $status2 = $master2->getVipStatus()?->value;

        return $status1 > $status2;
    }
}
