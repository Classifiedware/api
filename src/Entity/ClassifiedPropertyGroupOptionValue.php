<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ClassifiedPropertyGroupOptionValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassifiedPropertyGroupOptionValueRepository::class)]
class ClassifiedPropertyGroupOptionValue
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Classified::class, inversedBy: 'propertyGroupOptionValues')]
    private ?Classified $classified = null;

    #[ORM\ManyToOne(targetEntity: PropertyGroupOptionValue::class)]
    private ?PropertyGroupOptionValue $propertyGroupOptionValue = null;

    public function getClassified(): ?Classified
    {
        return $this->classified;
    }

    public function setClassified(?Classified $classified): self
    {
        $this->classified = $classified;

        return $this;
    }

    public function getPropertyGroupOptionValue(): ?PropertyGroupOptionValue
    {
        return $this->propertyGroupOptionValue;
    }

    public function setPropertyGroupOptionValue(?PropertyGroupOptionValue $propertyGroupOptionValue): self
    {
        $this->propertyGroupOptionValue = $propertyGroupOptionValue;

        return $this;
    }
}
