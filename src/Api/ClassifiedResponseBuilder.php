<?php declare(strict_types=1);

namespace App\Api;

use App\Api\Struct\ClassifiedStruct;
use App\Api\Struct\PropertyGroupOptionStruct;
use App\Api\Struct\ResponseStruct;

class ClassifiedResponseBuilder implements ClassifiedResponseBuilderInterface
{
    public function buildFromList(ResponseStruct $response): array
    {
        $buildItems = [];

        foreach ($response->getData() as $data) {
            $buildItems[] = $this->buildFromStruct($data);
        }

        return [
            'data' => $buildItems,
            /*'pagination' => [
                'totalPages' => $response->getTotalPages(),
                'hasPreviousPage' => $response->hasPreviousPage(),
                'hasNextPage' => $response->hasNextPage(),
            ],*/
        ];
    }

    private function buildFromStruct(ClassifiedStruct $struct): array
    {
        return [
            'id' => $struct->getId(),
            'name' => $struct->getName(),
            'description' => $struct->getDescription(),
            'price' => $this->formatPrice($struct->getPrice()),
            'offerNumber' => $struct->getOfferNumber(),
            'options' => array_map(fn (PropertyGroupOptionStruct $propertyGroupOption) => [
                'optionName' => $propertyGroupOption->getParentId() ? $propertyGroupOption->getGroupOptionNameParent() : $propertyGroupOption->getGroupName(),
                'value' => $propertyGroupOption->getName(),
            ], $struct->getPropertyGroupOptions()),
        ];
    }

    private function formatPrice(int $price): string
    {
        return number_format(($price / 100), 2, ',', '.');
    }
}