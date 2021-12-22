<?php
declare(strict_types=1);

namespace SlaveMarket\Lease;

/**
 * Арендованный час
 */
class LeaseHour
{
    /**
     * Время начала часа
     */
    private \DateTimeInterface $dateTime;

    public function __construct(string $dateTime)
    {
        $this->dateTime = \DateTime::createFromFormat('Y-m-d H', $dateTime);
    }

    /**
     * Возвращает строку, представляющую час
     */
    public function getDateString(): string
    {
        return $this->dateTime->format('Y-m-d H');
    }

    /**
     * Возвращает объект даты
     */
    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    /**
     * Возвращает дату аренды
     */
    public function getDate(): string
    {
        return $this->dateTime->format('Y-m-d');
    }

    /**
     * Возвращает час аренды
     *
     * @return string
     */
    public function getHour(): string
    {
        return $this->dateTime->format('H');
    }
}
