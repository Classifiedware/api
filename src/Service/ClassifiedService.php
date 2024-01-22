<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClassifiedDto;
use App\Dto\ClassifiedPropertyGroupOptionDto;
use App\Entity\Classified;
use App\Entity\PropertyGroupOption;
use App\Exception\ClassifiedValidationException;
use App\Repository\PropertyGroupOptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClassifiedService
{
    public function __construct(
        private readonly PropertyGroupOptionRepository $propertyGroupOptionRepository,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createClassified(ClassifiedDto $classifiedDto): Classified
    {
        $violations = $this->validator->validate($classifiedDto);

        if ($violations->count()) {
            throw new ClassifiedValidationException($violations);
        }

        $classified = new Classified();
        $classified->setUuid(Uuid::v4());
        $classified->setName($classifiedDto->getName());
        $classified->setDescription($classifiedDto->getDescription());
        $classified->setPrice($classifiedDto->getPrice());
        $classified->setOfferNumber($classifiedDto->getOfferNumber());
        $classified->setCreatedAt(new \DateTimeImmutable());

        foreach ($classifiedDto->getPropertyGroupOptions() as $propertyGroupOptionDto) {
            if ($propertyGroupOptionDto instanceof ClassifiedPropertyGroupOptionDto) {
                $propertyGroupOption = $this->propertyGroupOptionRepository->findOneBy([
                        'uuid' => Uuid::fromString($propertyGroupOptionDto->getPropertyGroupOptionId())->toBinary()
                    ]
                );

                if ($propertyGroupOption instanceof PropertyGroupOption) {
                    $classified->getPropertyGroupOptions()->add($propertyGroupOption);
                }
            }
        }

        $this->entityManager->persist($classified);
        $this->entityManager->flush();

        return $classified;
    }
}