<?php declare(strict_types=1);

namespace App\Api\Struct;

class ClassifiedStruct
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $description,
        private readonly int $price,
        private readonly string $offerNumber,
        private array $propertyGroupOptions = []
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getOfferNumber(): string
    {
        return $this->offerNumber;
    }

    /**
     * @return PropertyGroupOptionStruct[]
     */
    public function getPropertyGroupOptions(): array
    {
        return $this->propertyGroupOptions;
    }

    public function addPropertyGroupOption(PropertyGroupOptionStruct $propertyGroupOption): void
    {
        $this->propertyGroupOptions[] = $propertyGroupOption;
    }
}