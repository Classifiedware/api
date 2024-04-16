<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Repository\ClassifiedRepository;

class VehicleInteriorColorSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private string $propertyGroupId = '';

    private const GROUP_NAME = 'Innenausstattung';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
        $this->loadPropertyGroupId();
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        return $searchDto->hasPropertyGroup(self::GROUP_NAME);
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return $this->classifiedRepository->getPropertyGroupOptionIdsByGroupId($this->propertyGroupId, false);
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return $this->classifiedRepository->getExcludedPropertyGroupOptionIdsForEquipment($this->propertyGroupId, $searchDto->getPropertyGroupOptionIds(), false);
    }

    private function loadPropertyGroupId(): void
    {
        $this->propertyGroupId = $this->classifiedRepository->getPropertyGroupId(self::GROUP_NAME);
    }
}