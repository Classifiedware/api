<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupOptionStruct;
use App\Repository\ClassifiedRepository;

class VehicleConditionSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private const VEHICLE_CONDITIONS = [
        'Neufahrzeug',
        'Gebrauchtfahrzeug',
        'Dienstfahrzeug',
        'Jahresfahrzeug',
        'Vorführfahrzeug',
    ];

    private const GROUP_NAME = 'Fahrzeugzustand';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        return $searchDto->hasPropertyGroup(self::GROUP_NAME);
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        $propertyGroup = $searchDto->getPropertyGroups()[self::GROUP_NAME];
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        $excludedVehicleConditions = array_diff(self::VEHICLE_CONDITIONS, $propertyGroupOptionNames);

        return $this->classifiedRepository->getPropertyGroupOptionIds($propertyGroup->getGroupId(), $excludedVehicleConditions);
    }
}