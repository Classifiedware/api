<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PropertyGroupOptionValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyGroupOptionValueRepository::class)]
class PropertyGroupOptionValue
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne(targetEntity: PropertyGroupOption::class, inversedBy: 'optionValues')]
    private ?PropertyGroupOption $groupOption = null;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getGroupOption(): ?PropertyGroupOption
    {
        return $this->groupOption;
    }

    public function setGroupOption(?PropertyGroupOption $groupOption): self
    {
        $this->groupOption = $groupOption;

        return $this;
    }
}
