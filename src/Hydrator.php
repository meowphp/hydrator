<?php

namespace Meow\Hydrator;

use Meow\Hydrator\Exception\NotInstantiableClassException;

class Hydrator
{
    /**
     * Parse data from array to the class and return this class
     *
     * @param class-string $target
     * @param array $fields
     * @return object
     * @throws \ReflectionException
     * @throws NotInstantiableClassException
     */
    public function hydrate(string $target, array $fields): object
    {
        $reflection = $this->getReflectedClass($target);
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($fields as $k => $v) {
            if (!$reflection->hasProperty($k)) {
                continue;
            }

            $property = $reflection->getProperty($k);

            if ($property->isPrivate() || $property->isProtected()) {
                $property->setAccessible(true);
            }

            $property->setValue($object, $v);
        }

        return $object;
    }

    /**
     * Extracts requested properties and their values from class
     *
     * @param object $object
     * @param array $fields
     * @return array
     * @throws \ReflectionException
     * @throws NotInstantiableClassException
     */
    public function extract(object $object, array $fields): array
    {
        $reflection = $this->getReflectedClass($object);
        $result = [];

        foreach ($fields as $propertyName) {
            $property = $reflection->getProperty($propertyName);

            if ($property->isProtected() || $property->isPrivate()) {
                $property->setAccessible($propertyName);
            }

            $result[$property->getName()] = $property->getValue($object);
        }

        return $result;
    }

    /**
     * Returns reflection class
     *
     * @param class-string|object $target
     * @return \ReflectionClass<object>
     * @throws NotInstantiableClassException
     * @throws \ReflectionException
     */
    protected function getReflectedClass(string|object $target): \ReflectionClass
    {
        $className = is_object($target) ? get_class($target) : $target;
        $reflector = new \ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new NotInstantiableClassException($className);
        }

        return $reflector;
    }
}
