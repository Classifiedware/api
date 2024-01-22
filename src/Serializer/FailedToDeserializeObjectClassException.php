<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FailedToDeserializeObjectClassException extends BadRequestHttpException
{
    private ?ConstraintViolationListInterface $violations;

    public function __construct(
        string $objectClass,
        string $data,
        ?ConstraintViolationListInterface $violations,
        ?\Throwable $thrownException
    ) {
        parent::__construct(
            sprintf(
                'Failed to deserialize object class: %s with data %s',
                $objectClass,
                $data
            ),
            $thrownException
        );

        $this->violations = $violations;
    }

    public function getViolations(): ?ConstraintViolationListInterface
    {
        return $this->violations;
    }
}