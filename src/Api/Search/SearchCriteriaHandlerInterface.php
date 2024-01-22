<?php declare(strict_types=1);

namespace App\Api\Search;

interface SearchCriteriaHandlerInterface
{
    public function supports(ClassifiedSearchDto $searchDto): bool;

    public function getAllowedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array;

    public function getExcludedPropertyGroupOptionIds(ClassifiedSearchDto $searchDto): array;
}