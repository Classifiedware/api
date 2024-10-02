<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\Search\ClassifiedSearchDto;
use App\Api\Struct\ResponseStruct;

interface ClassifiedSearchListServiceInterface
{
    public function searchClassifieds(ClassifiedSearchDto $searchDto): ResponseStruct;
}