<?php

declare(strict_types=1);

namespace App\Controller\AdminApi;

use App\Dto\ClassifiedDto;
use App\Exception\ClassifiedValidationException;
use App\Service\ClassifiedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminClassifiedController extends AbstractController
{
    public function __construct(
        private readonly ClassifiedService $classifiedService,
    ) {
    }

    #[Route('/api/admin/classified/create', name: 'api-admin.classified-create', methods: ['POST'])]
    public function classifiedCreate(Request $request): Response
    {
        try {
            $result = $this->classifiedService->createClassified(new ClassifiedDto());

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
}
