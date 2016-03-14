<?php

namespace BartFeenstra\DependencyRetriever\DependencySuggestion;

/**
 * Provides a class constructor argument dependency suggestion.
 */
class Suggestion
{

    /**
     * The name of the dependency retriever.
     *
     * @var string
     */
    protected $dependencyRetrieverName;

    /**
     * The ID of the dependency with its retriever.
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
     *   The ID of the dependency with its retriever.
     */
    public function __construct($dependencyRetrieverName, $dependencyId)
    {
        $this->dependencyRetrieverName = $dependencyRetrieverName;
        $this->dependencyId = $dependencyId;
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
     * Gets the ID of the dependency with its retriever.
     *
     * @return string
     */
    public function getDependencyId()
    {
        return $this->dependencyId;
    }
}
