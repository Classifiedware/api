<?php declare(strict_types=1);

namespace App\Api;

use App\Api\Struct\ResponseStruct;

interface ClassifiedResponseBuilderInterface
{
    public function buildFromList(ResponseStruct $response): array;
}