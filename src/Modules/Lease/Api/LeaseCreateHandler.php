<?php

namespace SlaveMarket\Modules\Lease\Api;

use SlaveMarket\Modules\Lease\Domain\Command\LeaseRequestCommand;
use SlaveMarket\Modules\Lease\Domain\Logic\DateTimeRange\LeaseDateTimeRange;
use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator\СontractValidatorFactory;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;
use SlaveMarket\Modules\Master\Domain\Repository\MasterRepositoryInterface;
use SlaveMarket\Modules\Slave\Domain\Repository\SlavesRepositoryInterface;

/**
 * Операция "Арендовать раба"
 */
class LeaseCreateHandler
{
    public function __construct(
        private MasterRepositoryInterface $masterRepositpry,
        private SlavesRepositoryInterface $slaveRepository,
        private СontractValidatorFactory $contractValidatorFactory,
    ) {
    }

    /**
     * Выполнить операцию аренды.
     * Если аренда успешно оформлена - возвращается новый экземпляр аренды LeaseContract
     * Если в процессе аренды произошла ошибка, выбрасывается исключение LeaseRequestException с детальным описанием произошедшего
     *
     * @param LeaseRequestCommand $request
     * @return LeaseContract
     * @throws LeaseRequestException Если аренда не может быть выдана
     */
    public function handle(LeaseRequestCommand $request): LeaseContract
    {
        $master = $this->masterRepositpry->getById($request->masterId);
        $slave = $this->slaveRepository->getById($request->slaveId);

        /**
         * Создаем новый договор аренды
         */
        $leaseContract = new LeaseContract(
            master: $master,
            slave: $slave,
            dateTimeRange: new LeaseDateTimeRange(
                $request->dateFrom,
                $request->dateTo
            ),
        );

        /**
         * Проверка договора аренды на возможность оформления.
         * Проверяется, доступны ли запрошенные часы, не превышено ли рабочее время раба и т.д.
         *
         * @throws LeaseRequestException Если аренда не может быть выдана
         */
        foreach ($this->contractValidatorFactory->getValidators() as $validator) {
            $validator->validate($leaseContract);
        }

        /**
         * Списываем стоимость аренды со счета хозяина
         * Отрицательный баланс в данном случае не учитываем, потому что считаем, что запас золота у хозяина бесконечный
         */
        $master->minusGold($leaseContract->getPrice());

        /**
         * Сохранение контаркта
         */
        // Не реализовано

        /**
         * Сохранение хозяина
         */
        // Не реализовано

        return $leaseContract;
    }
}
