<?php
declare(strict_types=1);

namespace SlaveMarket\Master;

/**
 * Хозяин
 */
class Master
{
    public function __construct(
        private int $id,
        private string $name,
        private bool $isVIP = false,
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

    public function isVIP(): bool
    {
        return $this->isVIP;
    }
}
