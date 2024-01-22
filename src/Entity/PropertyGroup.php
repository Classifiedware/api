<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PropertyGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyGroupRepository::class)]
class PropertyGroup
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'propertyGroup', targetEntity: PropertyGroupOption::class)]
    private Collection $groupOptions;

    #[ORM\Column]
    private bool $isEquipmentGroup = false;

    public function __construct()
    {
        $this->groupOptions = new ArrayCollection();
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

    public function getGroupOptions(): Collection
    {
        return $this->groupOptions;
    }

    public function setGroupOptions(Collection $groupOptions): self
    {
        $this->groupOptions = $groupOptions;

        return $this;
    }

    public function addGroupOption(PropertyGroupOption $groupOption): self
    {
        if (!$this->groupOptions->contains($groupOption)) {
            $this->groupOptions->add($groupOption);
        }

        return $this;
    }

    public function isEquipmentGroup(): bool
    {
        return $this->isEquipmentGroup;
    }

    public function setIsEquipmentGroup(bool $isEquipmentGroup): self
    {
        $this->isEquipmentGroup = $isEquipmentGroup;

        return $this;
    }
}
