<?php

declare(strict_types=1);

namespace App\Controller\CustomerFrontendApi;

use App\Dto\ClassifiedSearchDto;
use App\Service\ClassifiedSearchListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

#[Route('/customer-frontend-api')]
class ClassifiedController extends AbstractController
{
    public function __construct(
        private readonly ClassifiedSearchListService $classifiedSearchListService,
        private readonly SerializerInterface $serializer
    )
    {
    }

    #[Route('/classified-search', name: 'app.customer-api.classified-search', methods: ['POST'])]
    public function classifiedSearch(Request $request): Response
    {
        try {
            $searchDto = $this->serializer->deserialize($request->getContent(), ClassifiedSearchDto::class, 'json', [
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            ]);
        } catch (PartialDenormalizationException $e) {
            $violations = new ConstraintViolationList();
            /** @var NotNormalizableValueException $exception */
            foreach ($e->getErrors() as $exception) {
                $message = sprintf('The type must be one of "%s" ("%s" given).', implode(', ', $exception->getExpectedTypes()), $exception->getCurrentType());
                $parameters = [];
                if ($exception->canUseMessageForUser()) {
                    $parameters['hint'] = $exception->getMessage();
                }
                $violations->add(new ConstraintViolation($message, '', $parameters, null, $exception->getPath(), null));
            }

            return $this->json($violations, 400);
        }

        $result = $this->classifiedSearchListService->searchClassifieds($searchDto);

        return $this->json(['data' => $result]);
    }
}
