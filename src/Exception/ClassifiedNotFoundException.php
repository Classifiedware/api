<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ClassifiedNotFoundException extends BadRequestHttpException
{
    private const MESSAGE = 'Classified with id "%s" not found.';

    public function __construct(string $classifiedId)
    {
        parent::__construct(sprintf(self::MESSAGE, $classifiedId));
    }
}