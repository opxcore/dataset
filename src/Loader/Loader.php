<?php


namespace OpxCore\DataSet\Loader;


use OpxCore\DataSet\Exceptions\InvalidTemplateDefinitionException;
use OpxCore\DataSet\Loader\Interfaces\CacheInterface;
use OpxCore\DataSet\Loader\Interfaces\ParserInterface;
use OpxCore\DataSet\Loader\Interfaces\ReaderInterface;
use OpxCore\DataSet\Loader\Traits\MakeFileName;
use OpxCore\DataSet\Template;
use OpxCore\DataSet\Utils\NameResolver;
use OpxCore\PathSet\PathSet;

class Loader implements Interfaces\LoaderInterface
{
    use MakeFileName;

    protected PathSet $paths;
    protected ReaderInterface $reader;
    protected ParserInterface $parser;
    protected ?CacheInterface $cache;
    protected ?array $options;

    /**
     * Loader constructor.
     *
     * @param PathSet $paths
     * @param ReaderInterface $reader
     * @param ParserInterface $parser
     * @param CacheInterface|null $cache
     * @param array|null $options
     *
     * @return  void
     */
    public function __construct(PathSet $paths, ReaderInterface $reader, ParserInterface $parser, ?CacheInterface $cache = null, ?array $options = null)
    {
        $this->paths = $paths;
        $this->reader = $reader;
        $this->parser = $parser;
        $this->cache = $cache;
        $this->options = $options;
    }


    /**
     * Find file with name in set of search paths and last modification timestamp.
     *
     * @param string $name
     * @param array|null $options
     *
     * @return  Template
     */
    public function get(string $name, ?array $options = null): Template
    {
        // $template = $this->load($name, $options);

        return new Template();
    }

    /**
     * @param string $name
     * @param array|null $options
     *
     * @return  Template
     */
    public function load(string $name, ?array $options = null): Template
    {
        [$namespace, $filename] = NameResolver::resolve($name);
        $paths = $this->paths->get($namespace);

        // First find requested template file
        $file = $this->reader->find($filename, $this->reader->extension(), $paths, $options);

        // Prepare cache name
        $cacheFileName = ($namespace === '*' ? null : $namespace . DIRECTORY_SEPARATOR) . $file->localPath() . DIRECTORY_SEPARATOR . $file->filename();

        // And look for requested template in cache with actual timestamp
        if ($this->cache !== null && $this->cache->has($cacheFileName, $file->timestamp())) {

            // If cache exists and actual get serialized template array
            $serialized = $this->cache->get($cacheFileName);

            $template = new Template(unserialize($serialized, ['allowed_classes' => false]));
        } else {
            // Otherwise read template array via reader
            $result = $this->reader->content($file);

            // Parse it
            $result = $this->parser->parse($result);

            // And cache it
            if ($this->cache !== null) {
                $this->cache->set($cacheFileName, serialize($result));
            }

            $template = new Template($result);
        }

        if (empty($options['not_extend']) && ($extends = $template->extends()) !== null) {
            if ($extends === $name) {
                throw new InvalidTemplateDefinitionException("Recursive extending found in [{$extends}]");
            }
            $template->extendWith($this->load($extends, $options));
        }

        return $template;
    }
}