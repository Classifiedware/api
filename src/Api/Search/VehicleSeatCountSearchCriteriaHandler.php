<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupOptionStruct;
use App\Api\Struct\PropertyGroupStruct;
use App\Repository\ClassifiedRepository;

class VehicleSeatCountSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private string $propertyGroupId = '';

    private string $propertyGroupOptionId = '';

    private const GROUP_NAME = 'Fahrzeugtyp';
    public const GROUP_OPTION_SEAT_COUNT = 'Anzahl Sitzplätze';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
        $this->loadPropertyGroupId();
        $this->loadPropertyGroupOptionId();
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        foreach ($searchDto->getPropertyGroupsSelectFrom() as $propertyGroup) {
            foreach ($propertyGroup->getOptions() as $option) {
                if ($option->getGroupOptionNameParent() === self::GROUP_OPTION_SEAT_COUNT) {
                    return true;
                }
            }
        }

        foreach ($searchDto->getPropertyGroupsSelectTo() as $propertyGroup) {
            foreach ($propertyGroup->getOptions() as $option) {
                if ($option->getGroupOptionNameParent() === self::GROUP_OPTION_SEAT_COUNT) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        $from = null;
        $to = null;

        $propertyGroupFrom = $searchDto->getPropertyGroupsSelectFrom()[self::GROUP_NAME] ?? null;
        if ($propertyGroupFrom instanceof PropertyGroupStruct) {
            $from = $this->getSeatCountFrom($propertyGroupFrom);
        }

        $propertyGroupTo = $searchDto->getPropertyGroupsSelectTo()[self::GROUP_NAME] ?? null;
        if ($propertyGroupTo instanceof PropertyGroupStruct) {
            $to = $this->getSeatCountTo($propertyGroupTo);
        }

        return $this->classifiedRepository->getPropertyGroupOptionIdsForRange(
            $this->propertyGroupId,
            $this->propertyGroupOptionId,
            $from,
            $to
        );
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    private function loadPropertyGroupId(): void
    {
        $this->propertyGroupId = $this->classifiedRepository->getPropertyGroupId(self::GROUP_NAME);
    }

    private function loadPropertyGroupOptionId(): void
    {
        $this->propertyGroupOptionId = $this->classifiedRepository->getPropertyGroupOptionId(self::GROUP_OPTION_SEAT_COUNT);
    }

    private function getSeatCountFrom(PropertyGroupStruct $propertyGroup): ?int
    {
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        if (\count($propertyGroupOptionNames) === 0) {
            return null;
        }

        return (int) min($propertyGroupOptionNames);
    }

    private function getSeatCountTo(PropertyGroupStruct $propertyGroup): ?int
    {
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        if (\count($propertyGroupOptionNames) === 0) {
            return null;
        }

        return (int) max($propertyGroupOptionNames);
    }
}