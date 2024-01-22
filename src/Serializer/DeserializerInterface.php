<?php

declare(strict_types=1);

namespace App\Serializer;

interface DeserializerInterface
{
    public function deserialize(string $data, string $objectClass): mixed;
}