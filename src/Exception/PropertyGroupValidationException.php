<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PropertyGroupValidationException extends BadRequestHttpException implements ValidationExceptionInterface
{
    private const MESSAGE = 'Unable to save PropertyGroup - PropertyGroupOptionStruct Validation Failed';

    private ConstraintViolationListInterface $violationList;

    public function __construct(
        ConstraintViolationListInterface $violationList,
        \Throwable $previous = null,
        int $code = 0,
        array $headers = []
    ) {
        parent::__construct(self::MESSAGE, $previous, $code, $headers);

        $this->violationList = $violationList;
    }

    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}