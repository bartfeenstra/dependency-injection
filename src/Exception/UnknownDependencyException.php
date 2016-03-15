<?php

namespace BartFeenstra\DependencyRetriever\Exception;

/**
 * Defines a situation in which a specified one or more constructor
 * dependencies are missing.
 */
class UnknownDependencyException extends \Exception
{

    /**
     * The name of the dependency retriever.
     *
     * @var string
     */
    protected $dependencyRetrieverName;

    /**
     * The ID of the unknown dependency.
     *
     * @var string
     */
    protected $dependencyId;

    /**
     * Constructs a new instance.
     *
     * @param string $dependencyRetrieverName
     *   The name of the dependency retriever.
     * @param string $dependencyId
     *   The ID of the unknown dependency.
     * @param \Exception|null $previousException
     *   The previous exception for chaining.
     */
    public function __construct($dependencyRetrieverName, $dependencyId, \Exception $previousException = null)
    {
        $this->dependencyRetrieverName = $dependencyRetrieverName;
        $this->dependencyId = $dependencyId;
        $message = sprintf(
            'Unknown dependency "%s" for dependency retriever %s.',
            $dependencyId,
            $dependencyRetrieverName
        );
        parent::__construct($message, 0, $previousException);
    }

    /**
     * Gets the name of the dependency retriever.
     *
     * @return string
     */
    public function getDependencyRetrieverName()
    {
        return $this->dependencyRetrieverName;
    }

    /**
     * Gets the name of the unknown dependency.
     *
     * @return string
     */
    public function getDependencyId()
    {
        return $this->dependencyId;
    }
}
