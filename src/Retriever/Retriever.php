<?php

namespace BartFeenstra\DependencyRetriever\Retriever;

/**
 * Defines a dependency retriever.
 */
interface Retriever
{

    /**
     * The PCRE pattern that defines a valid dependency retriever name.
     */
    const VALID_NAME_PCRE_PATTERN = '/^[a-z0-9-_]*$/';

    /**
     * Gets the dependency retriever's name.
     *
     * @return string
     *   The value MUST match self::VALID_NAME_PCRE_PATTERN.
     */
    public function getName();

    /**
     * Checks whether a dependency can be retrieved.
     *
     * @param string $id
     *   The ID of the dependency to retrieve.
     *
     * @return bool
     *   Whether the dependency is known.
     */
    public function knowsDependency($id);

    /**
     * Retrieves a dependency.
     *
     * @param string $id
     *   The ID of the dependency to retrieve.
     *
     * @return mixed
     *   The dependency.
     *
     * @throws \BartFeenstra\DependencyRetriever\Exception\MissingDependencyException
     *   Thrown if the requested dependency is unknown.
     */
    public function retrieveDependency($id);
}
