<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClassifiedDto;
use App\Dto\ClassifiedPropertyGroupOptionDto;
use App\Dto\ClassifiedSearchDto;
use App\Repository\ClassifiedRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClassifiedSearchListGateway implements ClassifiedSearchListGatewayInterface
{
    private const LIMIT_PER_PAGE = 10;

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository,
        private readonly ValidatorInterface $validator,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function getClassifiedsForSearch(ClassifiedSearchDto $searchDto): array
    {
        $result = $this->classifiedRepository->findClassifiedsForSearchList(
            $searchDto->getPage(),
            self::LIMIT_PER_PAGE,
            $searchDto->getPropertyGroupOptionValueIds()
        );

        $classifieds = [];
        foreach ($result as $row) {
            if (!is_array($row)) {
                continue;
            }

            $classifiedDto = (new ClassifiedDto())
                ->setId($row['id'] ?? null)
                ->setName($row['name'] ?? null)
                ->setDescription($row['description'] ?? null)
                ->setPrice($row['price'] ?? null)
                ->setOfferNumber($row['offerNumber'] ?? null);

            foreach ($row['propertyGroupOptionValues'] ?? [] as $propertyGroupOptionValueRow) {
                $classifiedPropertyGroupOptionDto = (new ClassifiedPropertyGroupOptionDto())
                    ->setPropertyGroupOptionValueId($propertyGroupOptionValueRow['id'] ?? null)
                    ->setGroupOptionName($propertyGroupOptionValueRow['groupOption']['name'] ?? null)
                    ->setGroupOptionType($propertyGroupOptionValueRow['groupOption']['type'] ?? null)
                    ->setValue($propertyGroupOptionValueRow['value'] ?? null);

                $classifiedDto->addPropertyGroupOption($classifiedPropertyGroupOptionDto);
            }

            $errors = $this->validator->validate($classifiedDto);

            if (count($errors) > 0) {
                $this->logger->info((string)$errors, ['classified' => $classifiedDto->toArray()]);

                continue;
            }

            $classifieds[] = $classifiedDto;
        }

        return $classifieds;
    }
}