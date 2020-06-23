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

class YamlFileReader extends FileReader
{
    /**
     * Get extension of files associated to reader.
     *
     * @return  string|null
     */
    public function extension(): ?string
    {
        return 'yaml';
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