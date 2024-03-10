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

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: PropertyGroupOption::class)]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: PropertyGroupOption::class, inversedBy: 'children')]
    private ?PropertyGroupOption $parent = null;

    #[ORM\Column]
    private bool $showInSearchList = false;

    #[ORM\Column]
    private bool $showInDetailPage = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $isModel = false;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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

    public function getPropertyGroup(): ?PropertyGroup
    {
        return $this->propertyGroup;
    }

    public function setPropertyGroup(?PropertyGroup $propertyGroup): self
    {
        $this->propertyGroup = $propertyGroup;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }

    public function getParent(): ?PropertyGroupOption
    {
        return $this->parent;
    }

    public function setParent(?PropertyGroupOption $parent): void
    {
        $this->parent = $parent;
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

    public function isModel(): bool
    {
        return $this->isModel;
    }

    public function setIsModel(bool $isModel): void
    {
        $this->isModel = $isModel;
    }
}
