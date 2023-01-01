<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ClassifiedSearchFailedException extends BadRequestHttpException
{
    private ?ConstraintViolationListInterface $violations;

    public function __construct(
        ?ConstraintViolationListInterface $violations,
        ?\Throwable $thrownException
    ) {
        parent::__construct('Classified Search has failed', $thrownException);

        $this->violations = $violations;
    }

    public function getViolations(): ?ConstraintViolationListInterface
    {
        return $this->violations;
    }
}