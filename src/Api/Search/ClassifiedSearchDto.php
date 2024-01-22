<?php

declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupStruct;
use Symfony\Component\Validator\Constraints as Assert;

class ClassifiedSearchDto
{
    #[Assert\Positive]
    private int $page = 1;

    private array $propertyGroupOptionIds = [];

    private array $propertyGroupOptionIdsSelectFrom = [];

    private array $propertyGroupOptionIdsSelectTo = [];

    /**
     * @var array<PropertyGroupStruct>
     */
    private array $propertyGroups = [];

    /**
     * @var array<PropertyGroupStruct>
     */
    private array $propertyGroupsSelectFrom = [];

    /**
     * @var array<PropertyGroupStruct>
     */
    private array $propertyGroupsSelectTo = [];

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPropertyGroupOptionIds(): array
    {
        return $this->propertyGroupOptionIds;
    }

    public function setPropertyGroupOptionIds(array $propertyGroupOptionIds): void
    {
        $this->propertyGroupOptionIds = $propertyGroupOptionIds;
    }

    public function getPropertyGroupOptionIdsSelectFrom(): array
    {
        return $this->propertyGroupOptionIdsSelectFrom;
    }

    public function setPropertyGroupOptionIdsSelectFrom(array $propertyGroupOptionIdsSelectFrom): void
    {
        $this->propertyGroupOptionIdsSelectFrom = $propertyGroupOptionIdsSelectFrom;
    }

    public function getPropertyGroupOptionIdsSelectTo(): array
    {
        return $this->propertyGroupOptionIdsSelectTo;
    }

    public function setPropertyGroupOptionIdsSelectTo(array $propertyGroupOptionIdsSelectTo): void
    {
        $this->propertyGroupOptionIdsSelectTo = $propertyGroupOptionIdsSelectTo;
    }

    public function getPropertyGroups(): array
    {
        return $this->propertyGroups;
    }

    public function setPropertyGroups(array $propertyGroups): void
    {
        $this->propertyGroups = $propertyGroups;
    }

    public function getPropertyGroupsSelectFrom(): array
    {
        return $this->propertyGroupsSelectFrom;
    }

    public function setPropertyGroupsSelectFrom(array $propertyGroupsSelectFrom): void
    {
        $this->propertyGroupsSelectFrom = $propertyGroupsSelectFrom;
    }

    public function getPropertyGroupsSelectTo(): array
    {
        return $this->propertyGroupsSelectTo;
    }

    public function setPropertyGroupsSelectTo(array $propertyGroupsSelectTo): void
    {
        $this->propertyGroupsSelectTo = $propertyGroupsSelectTo;
    }

    public function hasPropertyGroup(string $groupName): bool
    {
        return isset($this->propertyGroups[$groupName]);
    }
}