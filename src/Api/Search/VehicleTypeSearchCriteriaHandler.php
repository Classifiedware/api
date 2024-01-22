<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupOptionStruct;
use App\Repository\ClassifiedRepository;

class VehicleTypeSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private const VEHICLE_TYPES = [
        'Limousine',
        'Kombi',
        'Gelaendewagen/Pickup',
        'Cabrio/Roadster',
        'Sportwagen/Coupe',
        'Van/Kleinbus',
    ];

    private const GROUP_NAME = 'Fahrzeugtyp';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        $hasVehicleType = false;

        foreach ($searchDto->getPropertyGroups() as $propertyGroup) {
            foreach ($propertyGroup->getOptions() as $option) {
                if (in_array($option->getName(), self::VEHICLE_TYPES, true)) {
                    $hasVehicleType = true;
                }
            }
        }

        return $searchDto->hasPropertyGroup(self::GROUP_NAME) && $hasVehicleType;
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        $propertyGroup = $searchDto->getPropertyGroups()[self::GROUP_NAME];
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        $excludedVehicleTypes = array_diff(self::VEHICLE_TYPES, $propertyGroupOptionNames);

        return $this->classifiedRepository->getPropertyGroupOptionIds($propertyGroup->getGroupId(), $excludedVehicleTypes);
    }
}