<?php

declare(strict_types=1);

namespace App\Controller\CustomerFrontendApi;

use App\Api\ClassifiedSearchListServiceInterface;
use App\Api\Search\ClassifiedSearchDto;
use App\Serializer\DeserializerInterface;
use App\Service\PropertyService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer-frontend-api/search')]
class SearchController extends AbstractController
{
    public function __construct(
        private readonly DeserializerInterface $deserializer,
        private readonly PropertyService $propertyService,
        private readonly ClassifiedSearchListServiceInterface $classifiedSearchListService,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route('/property/options', name: 'app.customer-api.search.property-options', methods: ['GET'])]
    public function searchPropertyOptions(Request $request): Response
    {
        return $this->json(['data' => $this->propertyService->getProperties()]);
    }

    #[Route('/classified', name: 'app.customer-api.search.classified', methods: ['POST'])]
    public function searchClassified(Request $request): Response
    {
        try {
            $result = $this->classifiedSearchListService->searchClassifieds($this->deserializeDto($request));

            return $this->json($result);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
            ]);

            return $this->json(
                [
                    'data' => [],
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    private function deserializeDto(Request $request): ClassifiedSearchDto
    {
        return $this->deserializer->deserialize($request->getContent(), ClassifiedSearchDto::class);
    }
}
