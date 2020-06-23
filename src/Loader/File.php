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

use Carbon\Carbon;

class File
{
    /** @var string Name of file with local path (optional) related to $path */
    protected string $filename;

    /** @var string|null File extension */
    protected ?string $extension;
    /** @var mixed Path to file or array|object used by Reader to identify path */
    protected $path;

    /** @var string|null File path related to $path */
    protected ?string $localPath;

    /** @var Carbon|null Last modification timestamp */
    protected ?Carbon $lastModified;

    /**
     * File constructor.
     *
     * @param string $filename
     * @param string|null $extension
     * @param mixed $path
     * @param Carbon|null $lastModified
     *
     * @return  void
     */
    public function __construct(string $filename, ?string $extension, $path, ?Carbon $lastModified = null)
    {
        $info = pathinfo($filename . (!empty($extension) ? '.' . $extension : null));
        $this->filename = $info['filename'];
        $this->extension = $info['extension'] ?? null;
        $this->localPath = $info['dirname'];
        $this->path = $path;
        $this->lastModified = $lastModified;
    }

    /**
     * Get path to file or array|object used by Reader to identify path.
     *
     * @return  mixed
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Get a file local path related path.
     *
     * @return  mixed
     */
    public function localPath()
    {
        return $this->localPath;
    }

    /**
     * Get file name.
     *
     * @return  string
     */
    public function filename(): string
    {
        return $this->filename;
    }

    /**
     * Get file extension.
     *
     * @return  string|null
     */
    public function extension(): ?string
    {
        return $this->extension;
    }

    /**
     * Get last modified timestamp.
     *
     * @return  Carbon
     */
    public function timestamp(): Carbon
    {
        return $this->lastModified;
    }

    /**
     * Set last modified timestamp.
     *
     * @param Carbon $timestamp
     *
     * @return  void
     */
    public function setTimestamp(Carbon $timestamp): void
    {
        $this->lastModified = $timestamp;
    }

    /**
     * Check file age against given timestamp.
     *
     * @param Carbon $timestamp
     *
     * @return  bool
     */
    public function isNotNewerWhen(Carbon $timestamp): bool
    {
        return $this->lastModified <= $timestamp;
    }
}