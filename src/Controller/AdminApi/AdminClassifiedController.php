<?php

declare(strict_types=1);

namespace App\Controller\AdminApi;

use App\Dto\ClassifiedDto;
use App\Exception\ClassifiedValidationException;
use App\Serializer\DeserializerInterface;
use App\Service\ClassifiedService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminClassifiedController extends AbstractController
{
    public function __construct(
        private readonly ClassifiedService $classifiedService,
        private readonly DeserializerInterface $deserializer,
        private readonly LoggerInterface $logger,
    ) {
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
        $classifiedDto->setUploadedFiles($request->files->get('uploadedImages'));

        return $classifiedDto;
    }

}
