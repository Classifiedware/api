<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupOptionStruct;
use App\Repository\ClassifiedRepository;

class VehicleDoorCountSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private const DOOR_COUNTS = [
        '2/3',
        '4/5',
        '6/7'
    ];

    private const GROUP_NAME = 'Fahrzeugtyp';
    private const GROUP_OPTION_DOOR_COUNT = 'Anzahl Türen';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        foreach ($searchDto->getPropertyGroupsSelectFrom() as $propertyGroup) {
            foreach ($propertyGroup->getOptions() as $option) {
                if ($option->getGroupOptionNameParent() === self::GROUP_OPTION_DOOR_COUNT) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        $propertyGroup = $searchDto->getPropertyGroupsSelectFrom()[self::GROUP_NAME];
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        $excludedDoorCounts = array_diff(self::DOOR_COUNTS, $propertyGroupOptionNames);

        return $this->classifiedRepository->getPropertyGroupOptionIds($propertyGroup->getGroupId(), $excludedDoorCounts);
    }
}