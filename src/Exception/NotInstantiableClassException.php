<?php

namespace Meow\Hydrator\Exception;

use Throwable;

class NotInstantiableClassException extends \Exception
{
    /**
     * @param class-string $className
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $className, int $code = 1, Throwable $previous = null)
    {
        $message = "Class $className cannot be instantiated";

        parent::__construct($message, $code, $previous);
    }
}