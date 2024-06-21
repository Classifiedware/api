<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\File;
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
    private ?string $price;

    #[Assert\NotBlank]
    private ?string $offerNumber;

    private array $propertyGroupOptionIds = [];

    #[Assert\All([
        new Assert\Type(File::class),
    ])]
    #[Assert\Valid]
    private array $uploadedFiles = [];

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

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
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

    public function getPropertyGroupOptionIds(): array
    {
        return $this->propertyGroupOptionIds;
    }

    public function setPropertyGroupOptionIds(array $propertyGroupOptionIds): void
    {
        $this->propertyGroupOptionIds = $propertyGroupOptionIds;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function setUploadedFiles(array $uploadedFiles): void
    {
        $this->uploadedFiles = $uploadedFiles;
    }
}