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

use OpxCore\DataSet\Loader\File;

interface LoaderInterface
{
    /**
     * Find file with name in set of search paths and last modification timestamp.
     *
     * @param string $name
     * @param $searchPaths
     * @param array|null $options
     *
     * @return  File
     */
    public function get(string $name, $searchPaths, ?array $options = null): File;
}