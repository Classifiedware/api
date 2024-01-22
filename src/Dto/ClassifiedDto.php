<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ClassifiedDto
{
    #[Assert\NotBlank]
    private ?string $id;

    #[Assert\NotBlank]
    private ?string $name;

    #[Assert\NotBlank]
    private ?string $description;

    #[Assert\NotBlank]
    private ?int $price;

    #[Assert\NotBlank]
    private ?string $offerNumber;

    #[Assert\All(
        new Assert\Type(ClassifiedPropertyGroupOptionDto::class)
    )]
    #[Assert\Valid]
    private array $propertyGroupOptions = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOfferNumber(): string
    {
        return $this->offerNumber;
    }

    public function setOfferNumber(?string $offerNumber): self
    {
        $this->offerNumber = $offerNumber;

        return $this;
    }

    public function getPropertyGroupOptions(): array
    {
        return $this->propertyGroupOptions;
    }

    public function setPropertyGroupOptions(array $propertyGroupOptions): self
    {
        $this->propertyGroupOptions = $propertyGroupOptions;

        return $this;
    }

    public function addPropertyGroupOption(ClassifiedPropertyGroupOptionDto $classifiedPropertyGroupOption): self
    {
        $this->propertyGroupOptions[] = $classifiedPropertyGroupOption;

        return $this;
    }

    public function toArray(): array
    {
        $propertyGroupOptions = [];

        foreach ($this->propertyGroupOptions as $propertyGroupOption) {
            if (!$propertyGroupOption instanceof ClassifiedPropertyGroupOptionDto) {
                continue;
            }

            $propertyGroupOptions[] = $propertyGroupOption->toArray();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => number_format(($this->price / 100), 2, ',', '.'),
            'offerNumber' => $this->offerNumber,
            'options' => $propertyGroupOptions,
        ];
    }
}