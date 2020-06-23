<?php

namespace OpxCore\Tests\DataSet\Utils;

use OpxCore\DataSet\Utils\NameResolver;
use PHPUnit\Framework\TestCase;

class NameResolverTest extends TestCase
{
    public function testGlobalNameResolver(): void
    {
        $this->assertEquals(
            ['namespace' => '*', 'filename' => 'path/file'],
            NameResolver::resolve('path.file')
        );
    }

    public function testLocalNameResolver(): void
    {
        $this->assertEquals(
            ['namespace' => 'name', 'filename' => 'path/file'],
            NameResolver::resolve('name::path.file')
        );
    }
}
