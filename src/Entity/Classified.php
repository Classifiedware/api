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

    #[ORM\JoinTable(name: 'classified_property_group_option')]
    #[ORM\JoinColumn(name: 'classified_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'property_group_option_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: PropertyGroupOption::class)]
    private Collection $propertyGroupOptions;

    public function __construct()
    {
        $this->propertyGroupOptions = new ArrayCollection();
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

    public function getPropertyGroupOptions(): Collection
    {
        return $this->propertyGroupOptions;
    }

    public function setPropertyGroupOptions(Collection $propertyGroupOptions): self
    {
        $this->propertyGroupOptions = $propertyGroupOptions;

        return $this;
    }
}
