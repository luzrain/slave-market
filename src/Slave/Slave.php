<?php
declare(strict_types=1);

namespace SlaveMarket\Slave;

class Slave
{
    public function __construct(
        private int $id,
        private string $name,
        private float $pricePerHour,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPricePerHour(): float
    {
        return $this->pricePerHour;
    }
}
