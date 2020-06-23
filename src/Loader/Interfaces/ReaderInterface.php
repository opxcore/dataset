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

interface ReaderInterface
{
    /**
     * Get extension of files associated to reader.
     *
     * @return  string|null
     */
    public function extension(): ?string;

    /**
     * Find file with name in set of search paths and last modification timestamp.
     *
     * @param string $name
     * @param string|null $extension
     * @param string|array $paths
     * @param array|null $options
     *
     * @return  File
     */
    public function find(string $name, ?string $extension, $paths, ?array $options = null): File;

    /**
     * Load file contents.
     *
     * @param File $file
     *
     * @return  mixed
     */
    public function content(File $file);
}