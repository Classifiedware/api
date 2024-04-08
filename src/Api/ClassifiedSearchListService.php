<?php declare(strict_types=1);

namespace App\Api;

use App\Api\Search\ClassifiedSearchDto;
use App\Api\Search\SearchCriteriaHandler;
use App\Api\Struct\ClassifiedStruct;
use App\Api\Struct\PropertyGroupOptionStruct;
use App\Api\Struct\PropertyGroupStruct;
use App\Api\Struct\ResponseStruct;
use App\Repository\ClassifiedRepository;
use App\Service\PropertyService;

class ClassifiedSearchListService implements ClassifiedSearchListServiceInterface
{
    private const LIMIT_PER_PAGE = 10;

    public function __construct(
        private readonly ClassifiedResponseBuilderInterface $classifiedResponseBuilder,
        private readonly ClassifiedRepository $classifiedRepository,
        private readonly SearchCriteriaHandler $searchCriteriaHandler,
    ) {
    }

    public function searchClassifieds(ClassifiedSearchDto $searchDto): array
    {
        $this->enrichSearchDto($searchDto);

        //dd($searchDto->getPropertyGroups());

        $this->searchCriteriaHandler->handle($searchDto);

        $result = $this->classifiedRepository->findClassifiedsForSearchList(
            $searchDto->getPage(),
            self::LIMIT_PER_PAGE,
            $this->searchCriteriaHandler->getAllowedPropertyGroupOptionIds(),
            $this->searchCriteriaHandler->getExcludedPropertyGroupOptionIds()
        );

        $classifieds = [];
        foreach ($result as $row) {
            if (!\is_array($row)) {
                continue;
            }

            $classified = $this->buildClassified($row);

            foreach ($row['propertyGroupOptions'] ?? [] as $propertyGroupOptionRow) {
                $classified->addPropertyGroupOption(
                    $this->buildPropertyGroupOption($propertyGroupOptionRow)
                );
            }

            $classifieds[] = $classified;
        }

        return $this->classifiedResponseBuilder->buildFromList(
            new ResponseStruct(
                1,
                false,
                false,
                $classifieds
            )
        );
    }

    private function enrichSearchDto(ClassifiedSearchDto $searchDto): void
    {
        $propertyGroups = $this->buildPropertyGroups($searchDto->getPropertyGroupOptionIds());
        $propertyGroupsSelectFrom = $this->buildPropertyGroups($searchDto->getPropertyGroupOptionIdsSelectFrom());
        $propertyGroupsSelectTo = $this->buildPropertyGroups($searchDto->getPropertyGroupOptionIdsSelectTo());

        $searchDto->setPropertyGroups($propertyGroups);
        $searchDto->setPropertyGroupsSelectFrom($propertyGroupsSelectFrom);
        $searchDto->setPropertyGroupsSelectTo($propertyGroupsSelectTo);
    }

    /**
     * @return PropertyGroupStruct[]
     */
    private function buildPropertyGroups(array $propertyGroupOptionIds): array
    {
        $result = $this->classifiedRepository->getPropertyGroups($propertyGroupOptionIds);

        $propertyGroups = [];
        foreach ($result as $row) {
            $groupOptions = [];
            foreach ($row['groupOptions'] as $groupOption) {
                $parentId = null;
                if (\is_array($groupOption['parent'])) {
                    $parentId = (string)$groupOption['parent']['uuid'];
                }

                $groupOptions[] = new PropertyGroupOptionStruct(
                    (string)$groupOption['uuid'],
                    $parentId,
                    $groupOption['parent']['name'] ?? null,
                    (string)$row['uuid'],
                    $row['name'],
                    $groupOption['name']
                );
            }

            $propertyGroups[$row['name']] = new PropertyGroupStruct(
                (string)$row['uuid'],
                $row['name'],
                $groupOptions
            );

            unset($row);
        }

        return $propertyGroups;
    }

    private function buildClassified(array $row): ClassifiedStruct
    {
        return new ClassifiedStruct(
            (string)$row['uuid'],
            $row['name'],
            $row['description'],
            $row['price'],
            $row['offerNumber']
        );
    }

    private function buildPropertyGroupOption(array $row): PropertyGroupOptionStruct
    {
        $parentId = null;
        $groupOptionNameParent = null;

        if (\is_array($row['parent'])) {
            $parentId = (string)$row['parent']['uuid'];
            $groupOptionNameParent = $row['parent']['name'];

            if ($row['parent']['isModel'] === true) {
                $groupOptionNameParent = PropertyService::GROUP_OPTION_MODEL;
            }
        }

        return new PropertyGroupOptionStruct(
            (string)$row['uuid'],
            $parentId,
            $groupOptionNameParent,
            (string)$row['propertyGroup']['uuid'],
            $row['propertyGroup']['name'],
            $row['name']
        );
    }
}