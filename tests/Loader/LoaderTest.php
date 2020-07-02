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

    protected function makeCacheDriver(): FileCache
    {
        return new FileCache(sys_get_temp_dir() . '/cache_test/');
    }

    protected function initLoader(): Loader
    {
        $paths = new PathSet([
            '*' => [$this->path . '/assets/global'],
            'test' => [$this->path . '/assets/default', $this->path . '/assets/custom']
        ]);
        $reader = new YamlFileReader();
        $parser = new YamlParser();
        $cache = $this->makeCacheDriver();

        return new Loader($paths, $reader, $parser, $cache);
    }

    public function testLoader(): void
    {
        $loader = $this->initLoader();
        $template = $loader->load('test::subject.extend_template');

        $names = [];

        foreach ($template->fields as $field) {
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
