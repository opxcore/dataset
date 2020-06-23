<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Loader\Interfaces;

interface ParserInterface
{
    /**
     * Parse content to template initial array.
     *
     * @param $content
     *
     * @return  array
     */
    public function parse($content): array;
}