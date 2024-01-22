<?php declare(strict_types=1);

namespace App\Api\Struct;

class PropertyGroupStruct
{
    /**
     * @param array<PropertyGroupOptionStruct> $options
     */
    public function __construct(
        private readonly string $groupId,
        private readonly string $name,
        private readonly array $options
    ) {
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}