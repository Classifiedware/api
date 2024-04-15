<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Repository\ClassifiedRepository;

class BrandSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    private string $propertyGroupOptionId = '';

    private const GROUP_OPTION_NAME = 'Marke';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
        $this->loadPropertyGroupOptionId();
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        return $searchDto->getBrand() !== '' && $searchDto->getModel() === '';
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return $this->classifiedRepository->getExcludedPropertyGroupOptionIdsByParentId($this->propertyGroupOptionId, [$searchDto->getBrand()]);
    }

    private function loadPropertyGroupOptionId(): void
    {
        $this->propertyGroupOptionId = $this->classifiedRepository->getPropertyGroupOptionId(self::GROUP_OPTION_NAME);
    }
}