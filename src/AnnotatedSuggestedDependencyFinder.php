<?php

namespace BartFeenstra\DependencyRetriever;

use BartFeenstra\DependencyRetriever\Exception\ClassNotFoundException;

/**
 * Reads dependency suggestion annotations.
 */
class AnnotatedSuggestedDependencyFinder implements SuggestedDependencyFinder
{

    /**
     * The name of the annotation that documents dependencies.
     */
    const ANNOTATION_NAME = 'suggestedDependency';

    public function findSuggestedDependencyIds($className)
    {
        if (!class_exists($className)) {
            throw new ClassNotFoundException($className);
        }

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
            $suggestedDependencies[$argumentName][$matches[1][$i]] = $matches[2][$i];
        }

        return $suggestedDependencies;
    }

}
