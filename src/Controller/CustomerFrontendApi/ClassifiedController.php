<?php

namespace App\Controller\CustomerFrontendApi;

use App\Service\ClassifiedSearchListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer-frontend-api')]
class ClassifiedController extends AbstractController
{
    public function __construct(private readonly ClassifiedSearchListService $classifiedSearchListService)
    {
    }

    #[Route('/classified-list', name: 'app.customer-api.classified-list', methods: ['GET'])]
    public function index(): Response
    {
        $result = $this->classifiedSearchListService->searchClassifieds();

        return $this->json(['data' => $result]);
    }
}
