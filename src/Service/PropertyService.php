<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\PropertyGroupRepository;

class PropertyService
{
    private const GROUP_NAME_BRAND_MODEL = 'Marke, Modell, Variante';
    private const GROUP_OPTION_MODEL = 'Modell';
    private const GROUP_OPTION_BRAND = 'Marke';

    public function __construct(
        private readonly PropertyGroupRepository $propertyGroupRepository
    ) {
    }

    public function getProperties(): array
    {
        $propertyGroups = $this->propertyGroupRepository->getPropertyGroups();
        $brandModels = $this->getBrandModels($propertyGroups);

        $mappedPropertyGroups = [];
        foreach ($propertyGroups as $propertyGroup) {
            $propertyGroupOptions = $propertyGroup['groupOptions'] ?? [];

            $groupOptions = [];
            foreach ($propertyGroupOptions as $groupOption) {
                if (!isset($groupOption['parent'])) {
                    $optionValues = $this->getGroupsOptionsByParentId($groupOption['id'], $propertyGroupOptions);
                    if ($propertyGroup['name'] === self::GROUP_NAME_BRAND_MODEL && $groupOption['name'] === self::GROUP_OPTION_MODEL) {
                        $optionValues = $brandModels;
                    }

                    $groupOptions[] = [
                        'id' => $groupOption['uuid'],
                        'name' => $groupOption['name'],
                        'type' => $groupOption['type'],
                        'optionValues' => $optionValues,
                    ];
                }
            }

            $mappedPropertyGroups[] = [
                'name' => $propertyGroup['name'],
                'isEquipmentGroup' => $propertyGroup['isEquipmentGroup'],
                'groupOptions' => $groupOptions,
            ];
        }
        unset($propertyGroups);

        return $mappedPropertyGroups;
    }

    private function getBrandModels(array $propertyGroups): array
    {
        $models = [];
        foreach ($propertyGroups as $propertyGroup) {
            foreach ($propertyGroup['groupOptions'] as $groupOption) {
                if (isset($groupOption['parent']) && $groupOption['parent']['name'] === self::GROUP_OPTION_BRAND) {
                    foreach ($groupOption['children'] as $groupOptionChildren) {
                        if (count($groupOptionChildren['children']) === 0) {
                            $models[] = [
                                'id' => $groupOptionChildren['uuid'],
                                'parentName' => $groupOption['name'],
                                'value' => $groupOptionChildren['name'],
                            ];
                        }

                        if (count($groupOptionChildren['children']) > 0) {
                            $childValues = [];
                            foreach ($groupOptionChildren['children'] as $child) {
                                $childValues[] = [
                                    'id' => $child['uuid'],
                                    'value' => $child['name'],
                                ];
                            }

                            $models[] = [
                                'id' => $groupOptionChildren['uuid'],
                                'parentName' => $groupOption['name'],
                                'childName' => $groupOptionChildren['name'],
                                'values' => $childValues,
                            ];
                        }
                    }
                }
            }
        }

        return $models;
    }

    private function getGroupsOptionsByParentId(int $groupOptionId, array $groupOptions): array
    {
        $groupOptionByParentId = [];

        foreach ($groupOptions as $groupOption) {
            if (isset($groupOption['parent']) && $groupOption['parent']['id'] === $groupOptionId) {
                $groupOptionByParentId[] = [
                    'id' => $groupOption['uuid'],
                    'value' => $groupOption['name'],
                ];
            }
        }

        return $groupOptionByParentId;
    }
}