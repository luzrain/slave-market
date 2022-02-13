<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator;

use SlaveMarket\Modules\Lease\Domain\Exception\LeaseRequestException;
use SlaveMarket\Modules\Lease\Domain\Repository\LeaseContractRepositoryInterface;
use SlaveMarket\Modules\Lease\Persistence\Entity\LeaseContract;

/**
 * Абстрактный валидатор
 */
abstract class Validator
{
    /**
     * @param LeaseContractRepositoryInterface $contractRepository
     */
    public function __construct(
        protected LeaseContractRepositoryInterface $contractRepository,
    ) {
    }

    /**
     * Валидация договора аренды.
     * В случае, если валидация не проходит, выбрасывается исключение с детальным описанием произошедшей ошибки
     *
     * @param LeaseContract $leaseContract
     * @throws LeaseRequestException Если аренда не может быть выдана
     */
    abstract public function validate(LeaseContract $leaseContract): void;
}
