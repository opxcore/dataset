<?php

namespace OpxCore\Tests\DataSet\Loader\Parser;

use OpxCore\DataSet\Loader\Parser\DummyParser;
use PHPUnit\Framework\TestCase;

class DummyParserTest extends TestCase
{
    public function testParse(): void
    {
        $parser = new DummyParser();

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
            $parser->parse([
                'extends' => 'test',
                'fields' => [
                    'id' => [
                        'name' => 'id',
                    ],
                    'content' => [
                        'name' => 'content',
                    ],
                ]
            ])
        );
    }
}
