<?php

namespace BartFeenstra\DependencyRetriever;

/**
 * Defines a dependency retriever.
 */
interface DependencyRetriever
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
    public function respondsTo();

    /**
     * Checks whether a dependency can be retrieved.
     *
     * @param string $id
     *   The ID of the dependency to retrieve.
     *
     * @return bool
     *   Whether the dependency is known.
     */
    public function seesDependency($id);

    /**
     * Retrieves a dependency.
     *
     * @param string $id
     *   The ID of the dependency to retrieve.
     *
     * @return mixed
     *   The dependency.
     */
    public function retrieveDependency($id);

}
