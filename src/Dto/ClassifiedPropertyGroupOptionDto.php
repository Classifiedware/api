<?php

declare(strict_types=1);

namespace App\Dto;

class ClassifiedPropertyGroupOptionDto
{
    private ?string $propertyGroupOptionId;

    private ?string $groupOptionName;

    private ?string $groupOptionType;

    private ?string $value;

    public function getPropertyGroupOptionId(): ?string
    {
        return $this->propertyGroupOptionId;
    }

    public function setPropertyGroupOptionId(?string $propertyGroupOptionId): self
    {
        $this->propertyGroupOptionId = $propertyGroupOptionId;

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
        return [
            'optionName' => $this->groupOptionName,
            'value' => $this->value,
        ];
    }
}