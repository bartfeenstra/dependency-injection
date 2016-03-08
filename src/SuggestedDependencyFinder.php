<?php

namespace BartFeenstra\DependencyRetriever;

/**
 * Finds dependency suggestions for classes.
 */
interface SuggestedDependencyFinder
{

    /**
     * Finds a class' suggested constructor dependencies.
     *
     * @param string $className
     *   The fully qualified name of the class to retrieve dependency
     *   suggestions for.
     *
     * @return array[]
     *   Keys are constructor argument names, and values are arrays of which
     *   keys are dependency retriever names, and values are dependency IDs.
     *
     * @throws \BartFeenstra\DependencyRetriever\Exception\ClassNotFoundException
     *   Thrown if no class named $className could be found.
     */
    public function findSuggestedDependencyIds($className);

}
