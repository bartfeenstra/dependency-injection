<?php

namespace BartFeenstra\DependencyRetriever\DependencySuggestion;

/**
 * Finds dependency suggestions through annotations.
 */
class AnnotatedFinder implements Finder
{

    /**
     * The name of the annotation that documents dependencies.
     */
    const ANNOTATION_NAME = 'suggestedDependency';

    public function findSuggestedDependencyIds($className)
    {
        $class = new \ReflectionClass($className);

        // If the class has no constructor, it has no constructor dependencies
        // either.
        if (!$class->hasMethod('__construct')) {
            return [];
        }

        $method = $class->getMethod('__construct');
        $comment = $method->getDocComment();
        $matches = [];
        $pattern = sprintf('/@%s (.+?):(.+?) \$(.+)/', static::ANNOTATION_NAME);
        preg_match_all($pattern, $comment, $matches);
        $suggestedDependencies = [];
        foreach ($matches[3] as $i => $argumentName) {
            $suggestedDependencies[$argumentName][] = new Suggestion($matches[1][$i], $matches[2][$i]);
        }

        return $suggestedDependencies;
    }
}
