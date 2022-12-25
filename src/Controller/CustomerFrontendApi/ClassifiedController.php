<?php

declare(strict_types=1);

namespace App\Controller\CustomerFrontendApi;

use App\Dto\ClassifiedSearchDto;
use App\Serializer\DeserializerInterface;
use App\Serializer\FailedToDeserializeObjectClassException;
use App\Service\ClassifiedSearchListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer-frontend-api')]
class ClassifiedController extends AbstractController
{
    public function __construct(
        private readonly ClassifiedSearchListService $classifiedSearchListService,
        private readonly DeserializerInterface $deserializer
    )
    {
    }

    #[Route('/classified-search', name: 'app.customer-api.classified-search', methods: ['POST'])]
    public function classifiedSearch(Request $request): Response
    {
        try {
            $searchDto = $this->deserializer->deserialize($request->getContent(), ClassifiedSearchDto::class);
        } catch (FailedToDeserializeObjectClassException $e) {
            return $this->json(
                [
                    'data' => [],
                    'errors' => $e->getViolations(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $result = $this->classifiedSearchListService->searchClassifieds($searchDto);

        return $this->json(['data' => $result]);
    }
}
