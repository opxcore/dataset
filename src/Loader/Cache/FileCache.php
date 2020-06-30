<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Loader\Cache;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\Interfaces\CacheInterface;
use RuntimeException;

class FileCache implements CacheInterface
{
    /** @var string Path to store cached files */
    protected string $cachePath;

    /**
     * FileCache constructor.
     *
     * @param string $cachePath
     *
     * @return  void
     */
    public function __construct(string $cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * Whether cache has content with actual date.
     *
     * @param string|mixed $filename
     * @param Carbon $validFrom
     *
     * @return  bool
     */
    public function has($filename, Carbon $validFrom): bool
    {
        $fullName = $this->makeFilename($filename);

        if (!file_exists($fullName)) {
            return false;
        }

        $timestamp = @filemtime($fullName);

        if ($timestamp === false) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }

        return Carbon::parse($timestamp) >= $validFrom;
    }

    /**
     * Get content from cache.
     *
     * @param string|mixed $filename
     *
     * @return  mixed
     */
    public function get($filename)
    {
        return @file_get_contents($this->makeFilename($filename));
    }

    /**
     * Write content to cache.
     *
     * @param string|mixed $filename
     * @param string $content
     *
     * @return  void
     */
    public function set($filename, string $content): void
    {
        $name = $this->makeFilename($filename);
        // make directory recursive
        $dir = pathinfo($name, PATHINFO_DIRNAME);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
            // @codeCoverageIgnoreEnd
        }
        @file_put_contents($name, $content);
    }

    /**
     * Remove content from cache.
     *
     * @param string|mixed $filename
     *
     * @return  void
     */
    public function unset($filename): void
    {
        @unlink($this->makeFilename($filename));
    }

    /**
     * Make fully qualified file name.
     *
     * @param $filename
     *
     * @return  string
     */
    protected function makeFilename($filename): string
    {
        $name = $this->cachePath . DIRECTORY_SEPARATOR . $filename;

        // remove double separator in file name
        return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $name);
    }
}