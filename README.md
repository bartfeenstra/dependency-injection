# Dependency Retriever (woof!)

This package is a tool to make
[dependency injection](https://en.wikipedia.org/wiki/Dependency_injection) and 
class instantiation easier. Its API allows class' dependencies to be discovered 
and injected automatically by the factory.

Retrievers help you find dependencies, even if you can't or won't, by analyzing 
which dependencies class authors suggest you use:

    <?php
    
    use Psr\Log\LoggerInterface;
    
    /**
     * @suggestedDependency drupalContainerService:logger.channel.form $formLogger
     */
    class Bar {
    
      public function __construct(LoggerInterface $formLogger) {
        // ...
      }
    
    }
    ?>
This example declares then when used in a system in which Drupal's container 
if available, the `logger.channel.form` is a suggested dependency for the 
`$formLogger` parameter. The `drupalContainerService` retriever can retrieve 
this dependency and give it to the factory to be injected during class 
instantiation.
