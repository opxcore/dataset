<?php

namespace OpxCore\DataSet\Loader\Cache;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\Interfaces\CacheInterface;

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
        @file_put_contents($this->makeFilename($filename), $content);
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