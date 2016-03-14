<?php

namespace BartFeenstra\DependencyRetriever\DependencySuggestion;

/**
 * Finds dependency suggestions for classes.
 */
interface Finder
{

    /**
     * Finds a class' suggested constructor dependencies.
     *
     * @param string $className
     *   The fully qualified name of the class to retrieve dependency
     *   suggestions for.
     *
     * @return array[]
     *   Keys are constructor argument names, and values are
     *   BartFeenstra\DependencyRetriever\DependencySuggestion\Suggestion[]
     *   ordered by decreasing priority.
     */
    public function findSuggestedDependencyIds($className);

}
