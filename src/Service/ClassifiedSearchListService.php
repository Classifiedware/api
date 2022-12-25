<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClassifiedSearchDto;
use App\Repository\ClassifiedRepository;

class ClassifiedSearchListService
{
    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository
    )
    {
    }

    public function searchClassifieds(ClassifiedSearchDto $searchDto): array
    {
        $classifieds = $this->classifiedRepository->findClassifiedsForSearchList(
            $searchDto->getPage(),
            $searchDto->getItemsPerPage(),
            $searchDto->getPropertyGroupOptionValueIds()
        );

        $data = [];
        foreach ($classifieds as $classified) {
           /*if (!$classified instanceof Classified) {
               continue;
           }*/

           $data[] = $classified;
        }

        return $data;
    }
}