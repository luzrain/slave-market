<?php

namespace SlaveMarket\Modules\Lease\Domain\Logic\СontractValidator;

use Iterator;
use SlaveMarket\Modules\Lease\Domain\Repository\LeaseContractRepositoryInterface;

class СontractValidatorFactory
{
    public function __construct(
        private LeaseContractRepositoryInterface $contractRepository,
    ) {
    }

    /**
     * Возвращает список валидаторов для проверки договора аренды перед оформлением.
     *
     * @return Iterator<Validator>
     */
    public function getValidators(): Iterator
    {
        yield new Validators\ValidateSlaveIsAccessToLease($this->contractRepository);
        yield new Validators\ValidateSlaveWorkTime($this->contractRepository);
    }
}
