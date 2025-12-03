<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClassifiedDto;
use App\Entity\Classified;
use App\Entity\ClassifiedMedia;
use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use App\Exception\ClassifiedNotFoundException;
use App\Exception\ClassifiedValidationException;
use App\Repository\ClassifiedRepository;
use App\Repository\PropertyGroupOptionRepository;
use App\Repository\PropertyGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClassifiedService
{
    private const PROPERTY_GROUP_OPTION_DELIMITER = '|';

    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository,
        private readonly PropertyGroupRepository $propertyGroupRepository,
        private readonly PropertyGroupOptionRepository $propertyGroupOptionRepository,
        private readonly MediaUploadService $mediaUploadService,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function loadClassified(string $classifiedId): Classified
    {
        $classified = $this->classifiedRepository->findOneBy(['uuid' => $classifiedId]);
        if (!$classified instanceof Classified) {
            throw new ClassifiedNotFoundException($classifiedId);
        }

        return $classified;
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

        $selectedBrand = $this->getPropertyGroupOptionById($classifiedDto->getSelectedBrandId());
        $selectedModel = $this->getPropertyGroupOptionById($classifiedDto->getSelectedModelId());

        if ($selectedBrand instanceof PropertyGroupOption) {
            $classified->getPropertyGroupOptions()->add($selectedBrand);
        }

        if ($selectedModel instanceof PropertyGroupOption) {
            $classified->getPropertyGroupOptions()->add($selectedModel);
        }

        $this->addPropertyGroupOptionIds($classified, $classifiedDto->getPropertyGroupOptionIds());
        $this->addEnteredData($classifiedDto->getEnteredPropertyGroupOptionData(), $classified);

        $this->uploadClassifiedMedia($classifiedDto, $classified);

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

    private function addPropertyGroupOptionIds(Classified $classified, array $propertyGroupOptionIds): void
    {
        foreach ($propertyGroupOptionIds as $propertyGroupOptionId) {
            $propertyGroupOption = $this->getPropertyGroupOptionById($propertyGroupOptionId);

            if ($propertyGroupOption instanceof PropertyGroupOption) {
                $classified->getPropertyGroupOptions()->add($propertyGroupOption);
            }
        }
    }

    private function addEnteredData(array $parseableItems, Classified $classified): void
    {
        foreach ($parseableItems as $groupOptionData => $groupOptionValue) {
            $parsedGroupOption = explode(self::PROPERTY_GROUP_OPTION_DELIMITER, $groupOptionData);
            [$groupId, $groupOptionId] = $parsedGroupOption;

            $propertyGroupOptionParent = $this->getPropertyGroupOptionById($groupOptionId);

            $propertyGroup = $this->getPropertyGroupById($groupId);
            $createdPropertyGroupOption = $this->createPropertyGroupOption($groupOptionValue, $propertyGroup, $propertyGroupOptionParent);

            $classified->getPropertyGroupOptions()->add($createdPropertyGroupOption);
        }
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

    private function uploadClassifiedMedia(ClassifiedDto $classifiedDto, Classified $classified): void
    {
        $uploadedMedia = $this->mediaUploadService->uploadMedia($classifiedDto->getUploadedFiles());
        foreach ($uploadedMedia as $media) {
            $classifiedMedia = new ClassifiedMedia();
            $classifiedMedia->setUuid(Uuid::v4());
            $classifiedMedia->setClassified($classified);
            $classifiedMedia->setMedia($media);
            $classifiedMedia->setCreatedAt(new \DateTimeImmutable());

            $classified->addMedia($classifiedMedia);
        }
    }
}