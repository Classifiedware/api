<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Repository\ClassifiedRepository;

class ModelSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private string $propertyGroupId = '';

    private const GROUP_NAME = 'Marke, Modell, Variante';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
        $this->loadPropertyGroupId();
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        return $searchDto->getBrand() !== '' && $searchDto->getModel() !== '';
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        $propertyGroupIdsWhitelist = $this->classifiedRepository->getPropertyGroupOptionIdsByParentId($this->propertyGroupId, $searchDto->getModel());
        $propertyGroupIdsWhitelist[] = $searchDto->getModel();

        return $this->classifiedRepository->getExcludedPropertyGroupOptionIds($this->propertyGroupId, $propertyGroupIdsWhitelist);
    }

    private function loadPropertyGroupId(): void
    {
        $this->propertyGroupId = $this->classifiedRepository->getPropertyGroupId(self::GROUP_NAME);
    }
}