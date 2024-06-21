<?php

declare(strict_types=1);

namespace App\Controller\AdminApi;

use App\Service\PropertyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyGroupController extends AbstractController
{
    public function __construct(
        private readonly PropertyService $propertyService,
    ) {
    }

    #[Route('/api/admin/property/load', name: 'api-admin.property-load', methods: ['GET'])]
    public function propertyLoad(Request $request): Response
    {
        return $this->json(['data' => $this->propertyService->getProperties()]);
    }
}
