<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Loader\Traits;

use OpxCore\DataSet\Loader\File;

trait MakeFileName
{
    /**
     * Make full filename with path.
     *
     * @param File $file
     *
     * @return  string
     */
    protected function makeFileName(File $file): string
    {
        $name = $file->path() . DIRECTORY_SEPARATOR
            . $file->localPath() . DIRECTORY_SEPARATOR
            . $file->filename()
            . ($file->extension() ? ".{$file->extension()}" : null);

        // remove double separator in file name
        return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $name);
    }
}