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

use Carbon\Carbon;

interface CacheInterface
{
    /**
     * Whether cache has content with actual date.
     *
     * @param string|mixed $filename
     * @param Carbon $validFrom
     *
     * @return  bool
     */
    public function has($filename, Carbon $validFrom): bool;

    /**
     * Get content from cache.
     *
     * @param string|mixed $filename
     *
     * @return  mixed
     */
    public function get($filename);

    /**
     * Write content to cache.
     *
     * @param string|mixed $filename
     * @param string $content
     *
     * @return  void
     */
    public function set($filename, string $content): void;

    /**
     * Remove content from cache.
     *
     * @param string|mixed $filename
     *
     * @return  void
     */
    public function unset($filename): void;
}