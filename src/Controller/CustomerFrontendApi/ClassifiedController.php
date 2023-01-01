<?php

declare(strict_types=1);

namespace App\Controller\CustomerFrontendApi;

use App\Exception\ClassifiedSearchFailedException;
use App\Service\ClassifiedSearchListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer-frontend-api')]
class ClassifiedController extends AbstractController
{
    public function __construct(
        private readonly ClassifiedSearchListService $classifiedSearchListService
    )
    {
    }

    #[Route('/classified-search', name: 'app.customer-api.classified-search', methods: ['POST'])]
    public function classifiedSearch(Request $request): Response
    {
        try {
            $result = $this->classifiedSearchListService->searchClassifieds($request);

            return $this->json(['data' => $result]);
        } catch (ClassifiedSearchFailedException $e) {
            return $this->json(
                [
                    'data' => [],
                    'errors' => $e->getViolations(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
