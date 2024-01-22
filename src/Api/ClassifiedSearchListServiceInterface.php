<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\Search\ClassifiedSearchDto;

interface ClassifiedSearchListServiceInterface
{
    public function searchClassifieds(ClassifiedSearchDto $searchDto): array;
}