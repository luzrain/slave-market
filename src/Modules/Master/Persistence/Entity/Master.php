<?php

declare(strict_types=1);

namespace SlaveMarket\Modules\Master\Persistence\Entity;

use SlaveMarket\Modules\Master\Domain\Logic\Characteristics\Vip;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

/**
 * Объект хозяин
 */
final class Master
{
    /**
     * Id.
     */
    private Ulid $id;

    /**
     * Имя.
     */
    private string $name;

    /**
     * Баланс золота на счету.
     */
    private float $gold = 0;

    /**
     * VIP статус.
     */
    private ?Vip $vip = null;

    /**
     * @param string $name
     * @param float $gold
     * @param Vip|null $vip
     */
    public function __construct(
        string $name,
        float $gold = 0,
        ?Vip $vip = null,
    ) {
        $this->id = new Ulid();
        $this->name = $name;
        $this->gold = $gold;
        $this->vip = $vip;

        Assert::notEmpty($name);
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getGold(): float
    {
        return $this->gold;
    }

    public function addGold(float $gold): self
    {
        $this->gold += $gold;
        return $this;
    }

    public function minusGold(float $gold): self
    {
        $this->gold -= $gold;
        return $this;
    }

    public function isVip(): bool
    {
        return $this->vip !== null;
    }

    public function setVipStatus(?Vip $vip): self
    {
        $this->vip = $vip;
        return $this;
    }

    public function getVipStatus(): ?Vip
    {
        return $this->vip;
    }
}
