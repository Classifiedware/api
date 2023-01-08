<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClassifiedSearchDto;

interface ClassifiedSearchListGatewayInterface
{
    public function getClassifiedsForSearch(ClassifiedSearchDto $searchDto): array;
}