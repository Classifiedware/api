<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Classified;
use App\Repository\ClassifiedRepository;

class ClassifiedSearchListService
{
    public function __construct(private readonly ClassifiedRepository $classifiedRepository)
    {
    }

    public function searchClassifieds(): array
    {
        $classifieds = $this->classifiedRepository->findAll();

        $data = [];
        foreach ($classifieds as $classified) {
           if (!$classified instanceof Classified) {
               continue;
           }

           $data[] = $classified->getDataForCustomerFrontendApi();
        }

        return $data;
    }
}