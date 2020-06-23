<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Loader\Reader;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\Exceptions\FileNotFoundException;
use OpxCore\DataSet\Loader\Exceptions\FileReadErrorException;
use OpxCore\DataSet\Loader\Interfaces\ReaderInterface;
use OpxCore\DataSet\Loader\File;

class FileContentReader implements ReaderInterface
{
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
    public function find(string $name, ?string $extension, $paths, ?array $options = null): File
    {
        if (is_string($paths)) {
            $paths = [$paths];
        }

        foreach ($paths as $path) {
            $fullName = $this->makeFileName($path, $name, $extension);
            if (file_exists($fullName)) {
                $lastModified = @filemtime($fullName);

                return new File($name, $extension, $path, Carbon::parse($lastModified));
            }
        }

        throw new FileNotFoundException("Can not find file [{$name}] in paths ['" . implode("', '", $paths) . "']");
    }

    /**
     * Load file contents. Suggests file exists, this condition is checked in findFile.
     *
     * @param File $file
     *
     * @return  mixed
     *
     * @throws  FileReadErrorException
     */
    public function content(File $file)
    {
        $fullName = $this->makeFileName($file->getFilePath(), $file->getFileName());

        $content = $this->getContent($fullName);

        if ($content === false) {
            throw new FileReadErrorException("Can not read content of file [{$fullName}]");
        }

        return $content;
    }

    /**
     * Make full filename with path.
     *
     * @param string $path
     * @param string $filename
     * @param string|null $extension
     *
     * @return  string
     */
    protected function makeFileName(string $path, string $filename, ?string $extension = null): string
    {
        $name = $path . DIRECTORY_SEPARATOR . $filename . ($extension ? ".{$extension}" : $extension);

        // remove double separator in file name
        return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $name);
    }

    /**
     * Read file content.
     *
     * @param string $fileName
     *
     * @return  false|string
     */
    protected function getContent(string $fileName)
    {
        return @file_get_contents($fileName);
    }
}