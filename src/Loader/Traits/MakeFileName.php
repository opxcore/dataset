<?php

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