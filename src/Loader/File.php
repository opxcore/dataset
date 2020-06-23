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

    /** @var Carbon Last modification timestamp */
    protected Carbon $lastModified;

    /**
     * File constructor.
     *
     * @param string $filename
     * @param string|null $extension
     * @param mixed $path
     * @param Carbon $lastModified
     *
     * @return  void
     */
    public function __construct(string $filename, ?string $extension, $path, Carbon $lastModified)
    {
        $this->filename = $filename;
        $this->extension = $extension;
        $this->path = $path;
        $this->lastModified = $lastModified;
    }

    /**
     * Get path to file or array|object used by Reader to identify path.
     *
     * @return  mixed
     */
    public function getFilePath()
    {
        return $this->path;
    }

    /**
     * Get file name.
     *
     * @return  string
     */
    public function getFileName(): string
    {
        return $this->filename . ($this->extension ? ".{$this->extension}" : null);
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