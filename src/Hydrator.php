<?php

namespace Meow\Hydrator;

use Meow\Hydrator\Exception\NotInstantiableClassException;
use ReflectionType;
use Meow\Hydrator\Attributes\ArrayOf;
use ReflectionAttribute;
use ReflectionClass;

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

            if (is_array($v)) {
                $v = $this->parseArray($v, $k, $reflection);
            }

            $property = $reflection->getProperty($k);

            if ($property->isPrivate() || $property->isProtected()) {
                $property->setAccessible(true);
            }

            $v = @$v == null ? '' : $v;

            $property->setValue($object, $v);
        }

        return $object;
    }

    /**
     * Extracts requested properties and their values from class
     *
     * @param object $object
     * @param array|null $fields
     * @return array
     * @throws \ReflectionException
     * @throws NotInstantiableClassException
     */
    public function extract(object $object, ?array $fields = null): array
    {
        $reflection = $this->getReflectedClass($object);
        $result = [];

        if ($fields === [] || $fields === null) {
            $fields = array_map(function (\ReflectionProperty $property) {
                return $property->getName();
            }, $reflection->getProperties());
        }

        foreach ($fields as $propertyName) {
            $property = $reflection->getProperty($propertyName);

            if ($property->isProtected() || $property->isPrivate()) {
                $property->setAccessible($propertyName);
            }

            if (is_object($property->getValue($object))) {
                $result[$property->getName()] = $this->extract($property->getValue($object));
            } else {
                $result[$property->getName()] = $property->getValue($object);
            }
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

    /**
     * Undocumented function
     *
     * @param array $v value
     * @param string|integer $k key
     * @param ReflectionClass<object> $reflection
     * @return object|array Parsed array or array<object>
     */
    protected function parseArray(array $v, string|int $k, ReflectionClass $reflection): object|array
    {
        $t = $reflection->getProperty((string)$k)->getType();
        $attributes = $reflection->getProperty((string)$k)->getAttributes(ArrayOf::class);

        if (!empty($attributes)) {
            $ref = $attributes[0]->newInstance();
            $objectArray = [];

            if (class_exists($ref->getType())) {
                $refClass = new ReflectionClass($ref->getType());
                foreach ($v as $item) {
                    $objectArray[] = $this->hydrate($refClass->getName(), $item);
                }

                return $objectArray;
            }
        }

        /** @phpstan-ignore-next-line */
        return $v = $t->getName() === 'array' ? $v : $this->hydrate($t->getName(), $v);
    }
}
