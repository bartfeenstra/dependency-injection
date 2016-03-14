<?php

namespace BartFeenstra\DependencyRetriever\Exception;

/**
 * Defines a situation in which one or more constructor dependencies are
 * missing.
 */
class MissingDependencyException extends \Exception
{

    /**
     * The names of the missing arguments.
     *
     * @var string[]
     */
    protected $argumentNames;

    /**
     * The name of the class for which dependencies are missing.
     *
     * @var string
     */
    protected $className;

    /**
     * Constructs a new instance.
     *
     * @param string          $className
     *   The name of the class for which dependencies are missing.
     * @param string[]        $argumentNames
     *   The names of the missing arguments.
     * @param \Exception|null $previousException
     *   The previous exception for chaining.
     */
    public function __construct($className, array $argumentNames, \Exception $previousException = null)
    {
        $this->className = $className;
        $this->argumentNames = $argumentNames;
        $message = sprintf(
            'Missing dependency (value) for %s::__construct()\'s $%s argument(s).',
            $className,
            implode(', ', $argumentNames)
        );
        parent::__construct($message, 0, $previousException);
    }

    /**
     * Gets the name of the class for which dependencies are missing.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Gets the names of the missing arguments.
     *
     * @return string[]
     */
    public function getArgumentNames()
    {
        return $this->argumentNames;
    }
}
