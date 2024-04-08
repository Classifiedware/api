<?php declare(strict_types=1);

namespace App\Api\Search;

use App\Repository\ClassifiedRepository;

class BrandSearchCriteriaHandler implements SearchCriteriaHandlerInterface
{
    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    ) {
    }

    public function supports(ClassifiedSearchDto $searchDto): bool
    {
        return $searchDto->getBrand() !== '' && $searchDto->getModel() === '';
    }

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [$searchDto->getBrand()];
    }

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array
    {
        return [];
    }
}