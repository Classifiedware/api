<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupOptionStruct;
use App\Repository\ClassifiedRepository;

class VehicleFuelTypeSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private const FUEL_TYPES = [
        'Benzin',
        'Diesel',
        'PlugIn Hybrid-Benzin',
        'Elektro',
    ];

    private const GROUP_NAME = 'Motor';
    private const GROUP_OPTION_FUEL_TYPE = 'Kraftstoffart';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        $hasFuelType = false;

        foreach ($searchDto->getPropertyGroups() as $propertyGroup) {
            foreach ($propertyGroup->getOptions() as $option) {
                if ($option->getGroupOptionNameParent() === self::GROUP_OPTION_FUEL_TYPE) {
                    $hasFuelType = true;
                }
            }
        }

        return $searchDto->hasPropertyGroup(self::GROUP_NAME) && $hasFuelType;
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        $propertyGroup = $searchDto->getPropertyGroups()[self::GROUP_NAME];
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        $excludedFuelTypes = array_diff(self::FUEL_TYPES, $propertyGroupOptionNames);

        return $this->classifiedRepository->getPropertyGroupOptionIds($propertyGroup->getGroupId(), $excludedFuelTypes);
    }
}