<?php

declare(strict_types=1);

namespace App\Dto;

class ClassifiedSearchDto
{
    private int $page = 1;

    private int $itemsPerPage = 10;

    private array $propertyGroupOptionValueIds = [];

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getPropertyGroupOptionValueIds(): array
    {
        return $this->propertyGroupOptionValueIds;
    }

    public function setPropertyGroupOptionValueIds(array $propertyGroupOptionValueIds): void
    {
        $this->propertyGroupOptionValueIds = $propertyGroupOptionValueIds;
    }
}