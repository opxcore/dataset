<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Loader\Parser;

use OpxCore\DataSet\Loader\Interfaces\ParserInterface;

class DummyParser implements ParserInterface
{
    /**
     * Parse content to template initial array.
     *
     * @param $content
     *
     * @return  array
     */
    public function parse($content): array
    {
        return is_array($content) ? $content : [$content];
    }
}