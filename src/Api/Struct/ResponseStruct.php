<?php declare(strict_types=1);

namespace App\Api\Struct;

class ResponseStruct
{
    public function __construct(
        private readonly int $totalPages,
        private readonly bool $hasPreviousPage,
        private readonly bool $hasNextPage,
        private readonly array $data
    ) {
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function hasPreviousPage(): bool
    {
        return $this->hasPreviousPage;
    }

    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    public function getData(): array
    {
        return $this->data;
    }
}