<?php

declare(strict_types=1);

namespace App\ObjectManipulation;

class UpdateObject
{
    public function __invoke(object $object, object $objectDto): void
    {
        $objectDtoReflectionClass = new \ReflectionClass($objectDto);
        $objectEntityReflectionClass = new \ReflectionClass($object);

        foreach ($objectDtoReflectionClass->getProperties() as $objectDtoProperty) {
            $propertyName = $objectDtoProperty->getName();
            $propertyValue = $objectDtoProperty->getValue($objectDto);

            if ($propertyValue !== null) {
                $objectEntityProperty = $objectEntityReflectionClass->getProperty($propertyName);
                $objectEntityProperty->setValue($object, $propertyValue);
            }
        }
    }
}
