<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\PropertyGroupRepository;

class PropertyService
{
    public function __construct(
        private readonly PropertyGroupRepository $propertyGroupRepository
    ) {
    }

    public function getProperties(): array
    {
        $propertyGroups = $this->propertyGroupRepository->getPropertyGroups();
        $brandModels = $this->getBrandModels($propertyGroups);
        dd($brandModels);

        $mappedPropertyGroups = [];
        foreach ($propertyGroups as $propertyGroup) {
            $propertyGroupOptions = $propertyGroup['groupOptions'] ?? [];

            $groupOptions = [];
            foreach ($propertyGroupOptions as $groupOption) {
                if (!isset($groupOption['parent'])) {
                    $groupOptions[] = [
                        'id' => $groupOption['uuid'],
                        'name' => $groupOption['name'],
                        'type' => $groupOption['type'],
                        'optionValues' => $this->getGroupsOptionsByParentId($groupOption['id'], $propertyGroupOptions),
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
                if (isset($groupOption['parent']) && $groupOption['parent']['name'] === 'Marke') {
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