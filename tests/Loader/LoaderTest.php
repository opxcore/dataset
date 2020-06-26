<?php

namespace OpxCore\Tests\DataSet\Loader;

use OpxCore\DataSet\Field;
use OpxCore\DataSet\Loader\Cache\FileCache;
use OpxCore\DataSet\Loader\Loader;
use OpxCore\DataSet\Loader\Parser\YamlParser;
use OpxCore\DataSet\Loader\Reader\YamlFileReader;
use OpxCore\PathSet\PathSet;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    protected $path = __DIR__;

    protected function initLoader($withCache = true): Loader
    {
        $paths = new PathSet([
            '*' => [$this->path . '/assets/global'],
            'test' => [$this->path . '/assets/default', $this->path . '/assets/custom']
        ]);
        $reader = new YamlFileReader();
        $parser = new YamlParser();
        $cache = new FileCache($this->path . '/assets/cache');

        return new Loader($paths, $reader, $parser, $cache);
    }

    public function testLoader(): void
    {
        $loader = $this->initLoader();
        $template = $loader->load('test::subject.extend_template');

        $names = [];

        foreach ($template as $field) {
            /** @var Field $field */
            $names[] = $field->name();
        }

        $this->assertEquals(
            [
                'id',
                'title',
                'content',
            ],
            $names
        );
    }
}
