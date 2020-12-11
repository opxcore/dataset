<?php

namespace OpxCore\Tests\DataSet\Utils;

use OpxCore\DataSet\Utils\NameResolver;
use PHPUnit\Framework\TestCase;

class NameResolverTest extends TestCase
{
    public function testGlobalNameResolver(): void
    {
        self::assertEquals(
            ['*', 'path/file'],
            NameResolver::resolve('path.file')
        );
    }

    public function testLocalNameResolver(): void
    {
        self::assertEquals(
            ['name', 'path/file'],
            NameResolver::resolve('name::path.file')
        );
    }
}
