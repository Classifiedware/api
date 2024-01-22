<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Api\Struct\PropertyGroupOptionStruct;
use App\Api\Struct\PropertyGroupStruct;
use App\Repository\ClassifiedRepository;

class VehicleFirstRegistrationYearSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private string $propertyGroupId = '';

    private string $propertyGroupOptionId = '';

    private const GROUP_NAME = 'Basisdaten';
    public const GROUP_OPTION_FIRST_REGISTRATION_YEAR = 'Erstzulassung';

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
                if ($option->getGroupOptionNameParent() === self::GROUP_OPTION_FIRST_REGISTRATION_YEAR) {
                    return true;
                }
            }
        }

        foreach ($searchDto->getPropertyGroupsSelectTo() as $propertyGroup) {
            foreach ($propertyGroup->getOptions() as $option) {
                if ($option->getGroupOptionNameParent() === self::GROUP_OPTION_FIRST_REGISTRATION_YEAR) {
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
            $from = $this->getFirstRegistrationYearFrom($propertyGroupFrom);
        }

        $propertyGroupTo = $searchDto->getPropertyGroupsSelectTo()[self::GROUP_NAME] ?? null;
        if ($propertyGroupTo instanceof PropertyGroupStruct) {
            $to = $this->getFirstRegistrationYearTo($propertyGroupTo);
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
        $this->propertyGroupOptionId = $this->classifiedRepository->getPropertyGroupOptionId(self::GROUP_OPTION_FIRST_REGISTRATION_YEAR);
    }

    private function getFirstRegistrationYearFrom(PropertyGroupStruct $propertyGroup): ?int
    {
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        if (\count($propertyGroupOptionNames) === 0) {
            return null;
        }

        return (int) min($propertyGroupOptionNames);
    }

    private function getFirstRegistrationYearTo(PropertyGroupStruct $propertyGroup): ?int
    {
        $propertyGroupOptionNames = array_map(fn (PropertyGroupOptionStruct $struct) => $struct->getName(), $propertyGroup->getOptions());

        if (\count($propertyGroupOptionNames) === 0) {
            return null;
        }

        return (int) max($propertyGroupOptionNames);
    }
}