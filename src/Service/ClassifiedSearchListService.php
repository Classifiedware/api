<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ClassifiedSearchDto;
use App\Exception\ClassifiedSearchFailedException;
use App\Repository\ClassifiedRepository;
use App\Serializer\DeserializerInterface;
use App\Serializer\FailedToDeserializeObjectClassException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClassifiedSearchListService
{
    public function __construct(
        private readonly ClassifiedRepository $classifiedRepository,
        private readonly DeserializerInterface $deserializer,
        private readonly ValidatorInterface $validator,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function searchClassifieds(Request $request): array
    {
        try {
            /** @var ClassifiedSearchDto $searchDto */
            $searchDto = $this->deserializer->deserialize($request->getContent(), ClassifiedSearchDto::class);
        } catch (FailedToDeserializeObjectClassException $e) {
            $this->logger->error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
            ]);

            throw new ClassifiedSearchFailedException(null, $e);
        }

        $errors = $this->validator->validate($searchDto);

        if (count($errors) > 0) {
            throw new ClassifiedSearchFailedException($errors, null);
        }

        $classifieds = $this->classifiedRepository->findClassifiedsForSearchList(
            $searchDto->getPage(),
            $searchDto->getItemsPerPage(),
            $searchDto->getPropertyGroupOptionValueIds()
        );

        $data = [];
        foreach ($classifieds as $classified) {
           $data[] = $classified;
        }

        return $data;
    }
}