<?php

namespace BartFeenstra\DependencyRetriever\Exception;

/**
 * Defines a situation in which no class with a given name could be found.
 */
class MissingDependencyException extends \Exception
{

    /**
     * The argument for which a value is missing.
     *
     * @var \ReflectionParameter
     */
    protected $argument;

    /**
     * Constructs a new instance.
     *
     * @param \ReflectionParameter $argument
     *   The argument for which a value is missing.
     * @param \Exception|null $previousException
     *   The previous exception for chaining.
     */
    public function __construct(\ReflectionParameter $argument, \Exception $previousException = null)
    {
        $this->argument = $argument;
        $message = sprintf('Missing dependency (value) for %s::__construct()\'s $%s argument.', $argument->getClass()->getName(),
            $argument->getName());
        parent::__construct($message, 0, $previousException);
    }

    /**
     * Gets the argument for which a value is missing.
     *
     * @return \ReflectionParameter
     */
    public function getArgument()
    {
        return $this->argument;
    }

}
