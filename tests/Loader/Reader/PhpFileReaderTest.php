<?php

namespace OpxCore\Tests\DataSet\Loader\Reader;

use OpxCore\DataSet\Loader\Reader\PhpFileReader;
use PHPUnit\Framework\TestCase;

class PhpFileReaderTest extends TestCase
{
    protected string $assetsPath = __DIR__;

    public function testGetContent():void
    {
        $loader = new PhpFileReader;

        $templateFile = $loader->find('/assets/php_test', 'php', $this->assetsPath);

        $this->assertEquals(
            [
                'extends' => 'test',
                'fields' => [
                    'id' => [
                        'name' => 'id',
                    ],
                    'content' => [
                        'name' => 'content',
                    ],
                ]
            ],
            $loader->content($templateFile)
        );
    }
}
