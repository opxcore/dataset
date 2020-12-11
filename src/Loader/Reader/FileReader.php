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
use OpxCore\DataSet\Loader\Traits\MakeFileName;

abstract class FileReader implements ReaderInterface
{
    use MakeFileName;

    /**
     * Find file with name in set of search paths and last modification timestamp.
     *
     * @param string $name
     * @param string|null $extension
     * @param string|array $paths
     * @param array|null $options
     *
     * @return  File
     *
     * @throws  FileNotFoundException
     */
    public function find(string $name, ?string $extension, $paths, ?array $options = null): File
    {
        if (is_string($paths)) {
            $paths = [$paths];
        }

        foreach ($paths as $path) {
            $file = new File($name, $extension, $path);
            $fullName = $this->makeFileName($file);
            if (file_exists($fullName)) {
                $lastModified = @filemtime($fullName);
                $file->setTimestamp(Carbon::parse($lastModified));
                return $file;
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
        $fullName = $this->makeFileName($file);

        $content = $this->getContent($fullName);

        if ($content === false) {
            throw new FileReadErrorException("Can not read content of file [{$fullName}]");
        }

        return $content;
    }
}
