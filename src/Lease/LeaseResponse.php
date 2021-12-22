<?php
declare(strict_types=1);

namespace SlaveMarket\Lease;

/**
 * Результат операции аренды
 */
class LeaseResponse
{
    private LeaseContract|null $leaseContract = null;

    private array $errors = [];

    /**
     * Возвращает договор аренды, если аренда была успешной
     */
    public function getLeaseContract(): LeaseContract|null
    {
        return $this->leaseContract;
    }

    /**
     * Указать договор аренды
     */
    public function setLeaseContract(LeaseContract $leaseContract): void
    {
        $this->leaseContract = $leaseContract;
    }

    /**
     * Сообщить об ошибке
     */
    public function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * Возвращает все ошибки в процессе аренды
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
