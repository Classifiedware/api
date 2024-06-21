<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class JsonDeserializer implements DeserializerInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    #[\ReturnTypeWillChange]
    public function deserialize(mixed $data, string $objectClass): mixed
    {
        try {
            $deserializedObject = $this->serializer->deserialize($data, $objectClass, 'json', [
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            ]);

            if (!$deserializedObject instanceof $objectClass) {
                throw new FailedToDeserializeObjectClassException($objectClass, $data, null, null);
            }

            return $deserializedObject;
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

            throw new FailedToDeserializeObjectClassException($objectClass, $data, $violations, $e);
        } catch (NotEncodableValueException $e) {
            throw new FailedToDeserializeObjectClassException($objectClass, $data, null, $e);
        }
    }
}