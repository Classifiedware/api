<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupOptionStruct;
use App\Repository\ClassifiedRepository;

class VehicleTransmissionSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private const TRANSMISSIONS = [
        'Automatik',
        'Schaltgetriebe',
    ];

    private const GROUP_NAME = 'Motor';
    private const GROUP_OPTION_TRANSMISSION = 'Getriebe';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        $hasTransmission = false;

        foreach ($searchDto->getPropertyGroups() as $propertyGroup) {
            foreach ($propertyGroup->getOptions() as $option) {
                if ($option->getGroupOptionNameParent() === self::GROUP_OPTION_TRANSMISSION) {
                    $hasTransmission = true;
                }
            }
        }

        return $searchDto->hasPropertyGroup(self::GROUP_NAME) && $hasTransmission;
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        $propertyGroup = $searchDto->getPropertyGroups()[self::GROUP_NAME];
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        $excludedTransmissions = array_diff(self::TRANSMISSIONS, $propertyGroupOptionNames);

        return $this->classifiedRepository->getPropertyGroupOptionIds($propertyGroup->getGroupId(), $excludedTransmissions);
    }
}