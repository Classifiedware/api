<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\PropertyGroupOption;
use Symfony\Component\Validator\Constraints as Assert;

class ClassifiedPropertyGroupOptionDto
{
    #[Assert\NotBlank]
    private ?int $propertyGroupOptionValueId;

    #[Assert\NotBlank]
    private ?string $groupOptionName;

    #[Assert\NotBlank]
    private ?string $groupOptionType;

    #[Assert\NotBlank]
    private ?string $value;

    public function getPropertyGroupOptionValueId(): ?int
    {
        return $this->propertyGroupOptionValueId;
    }

    public function setPropertyGroupOptionValueId(?int $propertyGroupOptionValueId): self
    {
        $this->propertyGroupOptionValueId = $propertyGroupOptionValueId;

        return $this;
    }

    public function getGroupOptionName(): ?string
    {
        return $this->groupOptionName;
    }

    public function setGroupOptionName(?string $groupOptionName): self
    {
        $this->groupOptionName = $groupOptionName;

        return $this;
    }

    public function getGroupOptionType(): ?string
    {
        return $this->groupOptionType;
    }

    public function setGroupOptionType(?string $groupOptionType): self
    {
        $this->groupOptionType = $groupOptionType;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function toArray(): array
    {
        $value = $this->value;

        if ($this->groupOptionType === PropertyGroupOption::TYPE_CHECKBOX) {
            $value = true;
        }

        return [
            'optionName' => $this->groupOptionName,
            'value' => $value,
        ];
    }
}