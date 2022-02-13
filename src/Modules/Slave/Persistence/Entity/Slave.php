<?php

declare(strict_types=1);

namespace SlaveMarket\Modules\Slave\Persistence\Entity;

use DateTimeImmutable;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\Sex;
use SlaveMarket\Modules\Slave\Domain\Logic\Characteristics\SkinColor;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

/**
 * Раб
 */
final class Slave
{
    /**
     * Id
     */
    private Ulid $id;

    /**
     * Кличка
     */
    private string $name;

    /**
     * Пол
     */
    private Sex $sex;

    /**
     * Дата рождения (храним ее, вместо возраста)
     */
    private DateTimeImmutable $dob;

    /**
     * Вес
     */
    private float $weight;

    /**
     * Цвет кожи
     */
    private SkinColor $skinColor;

    /**
     * Где пойман/выращен
     */
    private string $grownPlace;

    /**
     * Описание и повадки (например, любит играть с собакой)
     */
    private string $description;

    /**
     * Ставка почасовой аренды
     */
    private float $pricePerHour;

    /**
     * Стоимость
     */
    private float $price;

    /**
     * @param string $name
     * @param Sex $sex
     * @param DateTimeImmutable $dob
     * @param float $weight
     * @param SkinColor $skinColor
     * @param string $grownPlace
     * @param string $description
     * @param float $pricePerHour
     * @param float $price
     */
    public function __construct(
        string $name,
        Sex $sex,
        DateTimeImmutable $dob,
        float $weight,
        SkinColor $skinColor,
        string $grownPlace = '',
        string $description = '',
        float $pricePerHour = 0,
        float $price = 0,
    ) {
        $this->id = new Ulid();
        $this->name = $name;
        $this->sex = $sex;
        $this->dob = $dob;
        $this->weight = $weight;
        $this->skinColor = $skinColor;
        $this->grownPlace = $grownPlace;
        $this->description = $description;
        $this->pricePerHour = $pricePerHour;
        $this->price = $price;

        Assert::notEmpty($name);
        Assert::notEmpty($sex);
        Assert::notEmpty($dob);
        Assert::notEmpty($weight);
        Assert::notEmpty($skinColor);
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

    public function getSex(): Sex
    {
        return $this->sex;
    }

    public function setSex(Sex $sex): self
    {
        $this->sex = $sex;
        return $this;
    }

    public function getDob(): DateTimeImmutable
    {
        return $this->dob;
    }

    public function setDob(DateTimeImmutable $dob): self
    {
        $this->dob = $dob;
        return $this;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getSkinColor(): SkinColor
    {
        return $this->skinColor;
    }

    public function setSkinColor(SkinColor $skinColor): self
    {
        $this->skinColor = $skinColor;
        return $this;
    }

    public function getGrownPlace(): string
    {
        return $this->grownPlace;
    }

    public function setGrownPlace(string $grownPlace): self
    {
        $this->grownPlace = $grownPlace;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPricePerHour(): float
    {
        return $this->pricePerHour;
    }

    public function setPricePerHour(float $pricePerHour): self
    {
        $this->pricePerHour = $pricePerHour;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }
}
