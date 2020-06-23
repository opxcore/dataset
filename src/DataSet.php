<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet;

use OpxCore\PathSet\PathSet;

class DataSet
{
    /**
     * Set of paths to search template files.
     *
     * @var PathSet|null
     */
    protected static ?PathSet $paths = null;

    /**
     * Add search path.
     *
     * @param string|null $namespace
     * @param string $path
     *
     * @return void
     */
    public static function addPath(?string $namespace, string $path): void
    {
        $namespace ??= '*';

        if (self::$paths === null) {
            self::$paths = new PathSet([$namespace => [$path]]);
        } else if (isset(self::$paths[$namespace])) {
            self::$paths[$namespace]->addAlternates([$path]);
        } else {
            self::$paths->add($namespace, $path);
        }
    }

    /**
     * Get paths for namespace or whole path set object.
     *
     * @param string|null $namespace
     *
     * @return array|PathSet
     */
    public static function getPaths(?string $namespace = null)
    {
        if ($namespace === null) {
            return self::$paths;
        }

        return self::$paths->get($namespace);
    }

    /**
     * Clear all registered paths.
     *
     * @return  void
     */
    public static function clearPaths(): void
    {
        self::$paths = null;
    }
}
