<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Loader;

use OpxCore\DataSet\Exceptions\InvalidTemplateDefinitionException;
use OpxCore\DataSet\Field;
use OpxCore\DataSet\Foundation\Collection;
use OpxCore\DataSet\Foundation\Policy;
use OpxCore\DataSet\Group;
use OpxCore\DataSet\Loader\Exceptions\FileNotFoundException;
use OpxCore\DataSet\Loader\Interfaces\CacheInterface;
use OpxCore\DataSet\Loader\Interfaces\ParserInterface;
use OpxCore\DataSet\Loader\Interfaces\ReaderInterface;
use OpxCore\DataSet\Loader\Traits\MakeFileName;
use OpxCore\DataSet\Section;
use OpxCore\DataSet\Template;
use OpxCore\DataSet\Utils\NameResolver;
use OpxCore\PathSet\PathSet;

class Loader implements Interfaces\LoaderInterface
{
    use MakeFileName;

    /** @var PathSet Set of paths to search template files */
    protected PathSet $paths;

    /** @var ReaderInterface File reader */
    protected ReaderInterface $reader;

    /** @var ParserInterface File content parser */
    protected ParserInterface $parser;

    /** @var CacheInterface|null Compiled template caching driver */
    protected ?CacheInterface $cache;

    /** @var array|null Options */
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
    public function load(string $name, ?array $options = null): Template
    {
        // Make namespace and filename from complete name
        // ('namespace::model.template' => ['namespace', 'model/template'])
        [$namespace, $filename] = NameResolver::resolve($name);

        // Find file in paths
        $file = $this->findFile($namespace, $filename, $options);

        // Try to load template from cache
        $template = $this->loadFromCache($namespace, $file, $options);

        // If no cached template
        if ($template === null) {

            // Read it via reader
            $content = $this->reader->content($file);

            // Parse it
            $templateArray = $this->parser->parse($content);

            // Make template
            $template = new Template($templateArray);

            // And cache whole template
            $this->storeToCache($namespace, $file, $template, $options);
        }

        // Check if template extends other
        if (
            empty($options['without_extending'])
            && $template->isExtendingEnabled()
            && ($extends = $template->extends()) !== null
        ) {

            // Prevent recursive extending
            if ($extends === $name) {
                throw new InvalidTemplateDefinitionException("Recursive extending found in [{$extends}]");
            }

            // Load and merge template to be extended
            $template->extend($this->load($extends, $options));
        }

        return $template;
    }

    /**
     * Find file for given namespace and filename.
     *
     * @param string $namespace
     * @param string $filename
     * @param array|null $options
     *
     * @return  File
     * @throws  FileNotFoundException
     * @internal
     */
    protected function findFile(string $namespace, string $filename, ?array $options): File
    {
        $paths = $this->paths->get($namespace);

        return $this->reader->find($filename, $this->reader->extension(), $paths, $options);
    }

    /**
     * Find and load template from cache if it set.
     *
     * @param string $namespace
     * @param File $file
     * @param array|null $options
     *
     * @return  Template|null
     * @internal
     */
    protected function loadFromCache(string $namespace, File $file, ?array $options): ?Template
    {
        // If cache not set we can't load anything from it.
        if (($this->cache === null) || ($options['without_cache'] ?? false === true)) {
            return null;
        }

        // Prepare cache name
        $cacheFileName = self::cacheFileName($namespace, $file);

        // Look for requested template in cache with actual timestamp
        if (!$this->cache->has($cacheFileName, $file->timestamp())) {
            return null;
        }

        // Load template
        $serialized = $this->cache->get($cacheFileName);

        // Return unserialized template
        return unserialize($serialized, ['allowed_classes' => [
            Template::class,
            Collection::class,
            Section::class,
            Group::class,
            Field::class,
            Policy::class,
        ]]);
    }

    /**
     * Store serialized template to cache if it set.
     *
     * @param string $namespace
     * @param File $file
     * @param Template $template
     * @param array|null $options
     *
     * @return  void
     * @internal
     */
    protected function storeToCache(string $namespace, File $file, Template $template, ?array $options = null): void
    {
        if ($this->cache !== null && ($options['without_cache'] ?? false === true) && $template->isCacheEnabled()) {
            $this->cache->set(self::cacheFileName($namespace, $file), serialize($template));
        }
    }

    /**
     * Generate filename for template cache file.
     *
     * @param string $namespace
     * @param File $file
     *
     * @return  string
     * @internal
     */
    protected static function cacheFileName(string $namespace, File $file): string
    {
        return ($namespace === '*' ? null : $namespace . DIRECTORY_SEPARATOR) . $file->localPath() . DIRECTORY_SEPARATOR . $file->filename();
    }
}