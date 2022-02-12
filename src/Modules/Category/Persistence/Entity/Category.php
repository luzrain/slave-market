<?php declare(strict_types=1);

namespace SlaveMarket\Modules\Category\Persistence\Entity;

use Symfony\Component\Uid\Ulid;

final class Category
{
    /**
     * Максимально количество рабочих часов в день для раба в категории.
     * Значение по умолчанию
     */
    private const MAX_WORK_TIME_PER_DAY = 16;

    /**
     * Id.
     */
    private Ulid $id;

    /**
     * Название категории.
     */
    private string $name;

    /**
     * Родительская категория.
     */
    private ?Category $parentCategory = null;

    /**
     * Максимальное количество часов, на которые может быть арендован раб.
     */
    private int $maxWorkTime = self::MAX_WORK_TIME_PER_DAY;

    public function __construct(
        string $name,
    ) {
        $this->id = new Ulid();
        $this->name = $name;
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

    public function getParentCategory(): ?Category
    {
        return $this->parentCategory;
    }

    public function setParentCategory(?Category $parentCategory): self
    {
        $this->parentCategory = $parentCategory;
        return $this;
    }

    public function getMaxWorkTime(): int
    {
        return $this->maxWorkTime;
    }

    public function setMaxWorkTime(int $maxWorkTime): self
    {
        $this->maxWorkTime = $maxWorkTime;
        return $this;
    }
}
