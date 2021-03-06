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

class PhpFileReader extends FileReader
{
    /**
     * Get extension of files associated to reader.
     *
     * @return  string|null
     */
    public function extension(): ?string
    {
        return 'php';
    }

    /**
     * Require PHP file.
     *
     * @param string $fileName
     *
     * @return  mixed
     */
    protected function getContent(string $fileName)
    {
        return require $fileName;
    }
}