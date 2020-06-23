<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Loader\Interfaces;

use OpxCore\DataSet\Template;
use OpxCore\PathSet\PathSet;

interface LoaderInterface
{
    /**
     * LoaderInterface constructor.
     *
     * @param PathSet $paths
     * @param ReaderInterface $reader
     * @param ParserInterface $parser
     * @param CacheInterface|null $cache
     * @param array|null $options
     *
     * @return  void
     */
    public function __construct(PathSet $paths, ReaderInterface $reader, ParserInterface $parser, ?CacheInterface $cache = null, ?array $options = null);

    /**
     * Find file with name in set of search paths and last modification timestamp.
     *
     * @param string $name
     * @param array|null $options
     *
     * @return  Template
     */
    public function get(string $name, ?array $options = null): Template;
}