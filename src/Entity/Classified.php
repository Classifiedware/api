<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ClassifiedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassifiedRepository::class)]
class Classified
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $offerNumber = null;

    #[ORM\OneToMany(mappedBy: 'classified', targetEntity: ClassifiedPropertyGroupOptionValue::class)]
    private Collection $propertyGroupOptionValues;

    public function __construct()
    {
        $this->propertyGroupOptionValues = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOfferNumber(): ?string
    {
        return $this->offerNumber;
    }

    public function setOfferNumber(?string $offerNumber): self
    {
        $this->offerNumber = $offerNumber;

        return $this;
    }

    public function getPropertyGroupOptionValues(): Collection
    {
        return $this->propertyGroupOptionValues;
    }

    public function setPropertyGroupOptionValues(Collection $propertyGroupOptionValues): self
    {
        $this->propertyGroupOptionValues = $propertyGroupOptionValues;

        return $this;
    }

    public function getDataForCustomerFrontendApi(): array
    {
        $propertyGroupOptions = [];

        foreach ($this->propertyGroupOptionValues as $propertyGroupOptionValue) {
            if ($propertyGroupOptionValue instanceof ClassifiedPropertyGroupOptionValue) {
                /** @var ClassifiedPropertyGroupOptionValue $propertyGroupOptionValue */
                $propertyGroupOption = $propertyGroupOptionValue->getPropertyGroupOptionValue()->getGroupOption();

                if ($propertyGroupOption instanceof PropertyGroupOption) {
                    $propertyGroupOptions[] = [
                        'name' => $propertyGroupOption->getName(),
                        'value' => $propertyGroupOptionValue->getPropertyGroupOptionValue()->getValue(),
                    ];
                }
            }
        }

        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'offerNumber' => $this->offerNumber,
            'propertyGroupOptions' => $propertyGroupOptions,
        ];
    }
}
