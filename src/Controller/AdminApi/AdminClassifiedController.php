<?php

declare(strict_types=1);

namespace App\Controller\AdminApi;

use App\Dto\ClassifiedDto;
use App\Entity\PropertyGroupOption;
use App\Exception\ClassifiedNotFoundException;
use App\Exception\ClassifiedValidationException;
use App\Serializer\DeserializerInterface;
use App\Service\ClassifiedService;
use App\Service\PropertyService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminClassifiedController extends AbstractController
{
    public function __construct(
        private readonly ClassifiedService $classifiedService,
        private readonly PropertyService $propertyService,
        private readonly DeserializerInterface $deserializer,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/api/admin/classified/{classifiedId}', name: 'api-admin.classified-load', methods: ['GET'])]
    public function classifiedLoad(string $classifiedId): Response
    {
        try {
            $classified = $this->classifiedService->loadClassified($classifiedId);

            $checkedPropertyGroupOptionIds = [];
            $selectedPropertyGroupOptionIds = [];
            $enteredPropertyGroupOptionData = [];
            $selectedBrand = '';
            $selectedModel = '';
            foreach ($classified->getPropertyGroupOptions() as $groupOption) {
                /** @var PropertyGroupOption $groupOption */
                if ($groupOption->getType() === PropertyGroupOption::TYPE_CHECKBOX
                    || $groupOption->getType() === PropertyGroupOption::TYPE_CHECKBOX_GROUP
                    || $groupOption->getType() === PropertyGroupOption::TYPE_MULTI_SELECT) {
                    $checkedPropertyGroupOptionIds[] = (string)$groupOption->getUuid();
                }

                if ($groupOption->getType() === PropertyGroupOption::TYPE_SELECT
                && $groupOption->getParent()->getName() !== 'Marke'
                && !$groupOption->isModel()
                ) {
                    $selectedPropertyGroupOptionIds[$groupOption->getPropertyGroup()->getUuid().'|'.$groupOption->getParent()->getUuid()] = (string)$groupOption->getUuid();
                }

                if ($groupOption->getType() === PropertyGroupOption::TYPE_SELECT_RANGE && $groupOption->getName() !== 'Preis (€)') {
                    $enteredPropertyGroupOptionData[$groupOption->getPropertyGroup()->getUuid().'|'.$groupOption->getParent()->getUuid()] = $groupOption->getName();
                }

                if ($groupOption->getType() === PropertyGroupOption::TYPE_SELECT && $groupOption->getParent()->getName() === 'Marke') {
                    $selectedBrand = $groupOption->getName().'|'.$groupOption->getUuid();
                }

                if ($groupOption->getType() === PropertyGroupOption::TYPE_SELECT && $groupOption->isModel()) {
                    $selectedModel = (string)$groupOption->getUuid();
                }
            }

            $classifiedData = [
                'id' => $classified->getUuid(),
                'name' => $classified->getName(),
                'description' => $classified->getDescription(),
                'price' => (string)$classified->getPrice(),
                'offerNumber' => $classified->getOfferNumber(),
                'checkedPropertyGroupOptionIds' => $checkedPropertyGroupOptionIds,
                'selectedPropertyGroupOptionIds' => $selectedPropertyGroupOptionIds,
                'enteredPropertyGroupOptionData' => $enteredPropertyGroupOptionData,
                'selectedBrand' => $selectedBrand,
                'selectedModel' => $selectedModel,
                'uploadedImages' => [],
                'propertyGroups' => $this->propertyService->getProperties(),
            ];

            return $this->json(['data' => $classifiedData]);
        } catch (ClassifiedNotFoundException $notFoundException) {
            return $this->json(['error' => $notFoundException->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/admin/classified/create', name: 'api-admin.classified-create', methods: ['POST'])]
    public function classifiedCreate(Request $request): Response
    {
        //$this->logger->debug('Creating classified', ['total' => $classifiedData]);

        try {
            $classifiedDto = $this->deserializeDto($request);

            $result = $this->classifiedService->createClassified($classifiedDto);

            return $this->json(['data' => $result]);
        } catch (ClassifiedValidationException $validationException) {
            return $this->json(
                [
                    'data' => [],
                    'errors' => $validationException->getViolationList(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    private function deserializeDto(Request $request): ClassifiedDto
    {
        $classifiedData = json_encode($request->request->all()['jsonData'] ?? []);
        /** @var ClassifiedDto $classifiedDto */
        $classifiedDto = $this->deserializer->deserialize($classifiedData, ClassifiedDto::class);
        $classifiedDto->setUploadedFiles($request->files->get('uploadedImages', []));

        return $classifiedDto;
    }

}
