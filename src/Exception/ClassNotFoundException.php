<?php

namespace BartFeenstra\DependencyRetriever\Exception;

/**
 * Defines a situation in which no class with a given name could be found.
 */
class ClassNotFoundException extends \Exception
{

    /**
     * The name of the class that could not be found.
     *
     * @var string
     */
    protected $className;

    /**
     * Constructs a new instance.
     *
     * @param string $className
     *   The name of the class that could not be found.
     * @param \Exception|null $previousException
     *   The previous exception for chaining.
     */
    public function __construct($className, \Exception $previousException = null)
    {
        if (!is_string($className)) {
            $classNameType = is_object($className) ? get_class($className) : gettype($className);
            throw new \InvalidArgumentException(sprintf('$className must be a string, but %s was given.',
                $classNameType));
        }

        $this->className = $className;
        $message = sprintf('Class "%s" could not be found.', $className);
        parent::__construct($message, 0, $previousException);
    }

    /**
     * Gets the name of the class that could not be found.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

}
