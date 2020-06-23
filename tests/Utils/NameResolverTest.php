<?php

namespace OpxCore\Tests\DataSet\Utils;

use OpxCore\DataSet\Utils\NameResolver;
use PHPUnit\Framework\TestCase;

class NameResolverTest extends TestCase
{
    public function testGlobalNameResolver(): void
    {
        $this->assertEquals(
            ['*', 'path/file'],
            NameResolver::resolve('path.file')
        );
    }

    public function testLocalNameResolver(): void
    {
        $this->assertEquals(
            ['name', 'path/file'],
            NameResolver::resolve('name::path.file')
        );
    }
}
