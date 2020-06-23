<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Utils;

class NameResolver
{
    /**
     * Resolve name to namespace and filename.
     *
     * @param string $name
     *
     * @return array
     */
    public static function resolve(string $name): array
    {
        $parts = explode('::', $name, 2);

        $global = !isset($parts[1]);
        $namespace = $global ? '*' : $parts[0];
        $filename = str_replace('.', DIRECTORY_SEPARATOR, $global ? $parts[0] : $parts[1]);

        return [
            'namespace' => $namespace,
            'filename' => $filename
        ];
    }
}
