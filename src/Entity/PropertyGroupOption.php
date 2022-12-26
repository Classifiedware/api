<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PropertyGroupOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyGroupOptionRepository::class)]
class PropertyGroupOption
{
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_CHECKBOX_GROUP = 'checkboxGroup';
    public const TYPE_SELECT = 'select';
    public const TYPE_MULTI_SELECT = 'multiSelect';
    public const TYPE_TEXT_FIELD = 'textField';
    public const TYPE_SELECT_RANGE = 'selectRange';

    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: PropertyGroup::class, inversedBy: 'groupOptions')]
    private ?PropertyGroup $propertyGroup = null;

    #[ORM\OneToMany(mappedBy: 'groupOption', targetEntity: PropertyGroupOptionValue::class)]
    private Collection $optionValues;

    #[ORM\Column]
    private bool $showInSearchList = false;

    #[ORM\Column]
    private bool $showInDetailPage = false;

    public function __construct()
    {
        $this->optionValues = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOptionValues(): Collection
    {
        return $this->optionValues;
    }

    public function setOptionValues(Collection $optionValues): self
    {
        $this->optionValues = $optionValues;

        return $this;
    }

    public function getPropertyGroup(): ?PropertyGroup
    {
        return $this->propertyGroup;
    }

    public function setPropertyGroup(?PropertyGroup $propertyGroup): self
    {
        $this->propertyGroup = $propertyGroup;

        return $this;
    }

    public function isShowInSearchList(): bool
    {
        return $this->showInSearchList;
    }

    public function setShowInSearchList(bool $showInSearchList): self
    {
        $this->showInSearchList = $showInSearchList;

        return $this;
    }

    public function isShowInDetailPage(): bool
    {
        return $this->showInDetailPage;
    }

    public function setShowInDetailPage(bool $showInDetailPage): self
    {
        $this->showInDetailPage = $showInDetailPage;

        return $this;
    }
}
