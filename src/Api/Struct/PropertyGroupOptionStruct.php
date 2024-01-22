<?php declare(strict_types=1);

namespace App\Api\Struct;

class PropertyGroupOptionStruct
{
    public function __construct(
        private readonly string $groupOptionId,
        private readonly ?string $parentId,
        private readonly ?string $groupOptionNameParent,
        private readonly string $groupId,
        private readonly string $groupName,
        private readonly string $name
    ) {
    }

    public function getGroupOptionId(): string
    {
        return $this->groupOptionId;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getGroupOptionNameParent(): ?string
    {
        return $this->groupOptionNameParent;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function getGroupName(): string
    {
        return $this->groupName;
    }

    public function getName(): string
    {
        return $this->name;
    }
}