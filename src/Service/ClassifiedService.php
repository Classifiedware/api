<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClassifiedDto;
use App\Entity\Classified;
use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use App\Exception\ClassifiedValidationException;
use App\Repository\PropertyGroupOptionRepository;
use App\Repository\PropertyGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClassifiedService
{
    private const PROPERTY_GROUP_OPTION_DELIMITER = '|';

    public function __construct(
        private readonly PropertyGroupRepository $propertyGroupRepository,
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
        $classified->setPrice($classifiedDto->getPrice() * 100);
        $classified->setOfferNumber($classifiedDto->getOfferNumber());
        $classified->setCreatedAt(new \DateTimeImmutable());

        foreach ($classifiedDto->getPropertyGroupOptionIds() as $propertyGroupOptionId) {
            $propertyGroupOption = $this->getPropertyGroupOptionById($propertyGroupOptionId);

            if ($propertyGroupOption instanceof PropertyGroupOption) {
                $classified->getPropertyGroupOptions()->add($propertyGroupOption);
            }
        }

        foreach ($classifiedDto->getEnteredPropertyGroupOptionData() as $groupOptionData => $groupOptionValue) {
            $parsedGroupOption = explode(self::PROPERTY_GROUP_OPTION_DELIMITER, $groupOptionData);
            [$groupOptionId, $groupId] = $parsedGroupOption;

            $propertyGroupOptionParent = $this->getPropertyGroupOptionById($groupOptionId);

            $propertyGroupOption = $this->propertyGroupOptionRepository->findOneBy([
                    'parent' => $propertyGroupOptionParent,
                    'name' => $groupOptionValue,
                ]
            );

            if ($propertyGroupOption instanceof PropertyGroupOption) {
                $classified->getPropertyGroupOptions()->add($propertyGroupOption);
            }

            if (!$propertyGroupOption instanceof PropertyGroupOption) {
                $propertyGroup = $this->getPropertyGroupById($groupId);
                $createdPropertyGroupOption = $this->createPropertyGroupOption($groupOptionValue, $propertyGroup, $propertyGroupOptionParent);

                $classified->getPropertyGroupOptions()->add($createdPropertyGroupOption);
            }
        }

        // TODO: handle upload images

        $this->entityManager->persist($classified);
        $this->entityManager->flush();

        return $classified;
    }

    private function getPropertyGroupOptionById(string $id): ?PropertyGroupOption
    {
        return $this->propertyGroupOptionRepository->findOneBy([
                'uuid' => Uuid::fromString($id)->toBinary(),
            ]
        );
    }

    private function getPropertyGroupById(string $id): ?PropertyGroup
    {
        return $this->propertyGroupRepository->findOneBy([
                'uuid' => Uuid::fromString($id)->toBinary(),
            ]
        );
    }

    private function createPropertyGroupOption(
        string $name,
        PropertyGroup $propertyGroup,
        PropertyGroupOption $propertyGroupOptionParent
    ): PropertyGroupOption {
        $propertyGroupOption = new PropertyGroupOption();
        $propertyGroupOption->setUuid(Uuid::v4());
        $propertyGroupOption->setPropertyGroup($propertyGroup);
        $propertyGroupOption->setName($name);
        $propertyGroupOption->setType(PropertyGroupOption::TYPE_SELECT_RANGE);
        $propertyGroupOption->setShowInSearchList(false);
        $propertyGroupOption->setShowInDetailPage(true);
        $propertyGroupOption->setParent($propertyGroupOptionParent);
        $propertyGroupOption->setIsModel(false);
        $propertyGroupOption->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($propertyGroupOption);
        $this->entityManager->flush();

        return $propertyGroupOption;
    }
}