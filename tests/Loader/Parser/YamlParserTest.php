<?php

namespace OpxCore\Tests\DataSet\Loader\Parser;

use OpxCore\DataSet\Loader\Parser\YamlParser;
use PHPUnit\Framework\TestCase;

class YamlParserTest extends TestCase
{
    protected string $path = __DIR__;

    public function testParse(): void
    {
        $parser = new YamlParser();

        $content = file_get_contents($this->path . '/fixtures/parser_test.yaml');

        self::assertEquals(
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
            $parser->parse($content)
        );
    }
}
