<?php

namespace OpxCore\Tests\DataSet\Loader;

use FilesystemIterator;
use OpxCore\DataSet\Exceptions\InvalidTemplateDefinitionException;
use OpxCore\DataSet\Field;
use OpxCore\DataSet\Loader\Cache\FileCache;
use OpxCore\DataSet\Loader\Loader;
use OpxCore\DataSet\Loader\Parser\YamlParser;
use OpxCore\DataSet\Loader\Reader\YamlFileReader;
use OpxCore\PathSet\PathSet;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    protected string $path = __DIR__;

    protected function makeCacheDriver(string $cachePath, bool $inTemp = true): FileCache
    {
        if ($inTemp) {
            $path = sys_get_temp_dir() . "/$cachePath/";
        } else {
            $path = $cachePath;
        }
        return new FileCache($path);
    }

    protected function initLoader(string $cachePath = 'cache_test', bool $inTemp = true): Loader
    {
        $paths = new PathSet([
            '*' => [$this->path . '/assets/global'],
            'test' => [$this->path . '/assets/default', $this->path . '/assets/custom']
        ]);
        $reader = new YamlFileReader();
        $parser = new YamlParser();
        $cache = $this->makeCacheDriver($cachePath, $inTemp);

        return new Loader($paths, $reader, $parser, $cache);
    }

    protected function recursiveRemoveDir($dir): void
    {

        $includes = new FilesystemIterator($dir);

        foreach ($includes as $include) {

            if (is_dir($include) && !is_link($include)) {
                $this->recursiveRemoveDir($include);
            } else {
                unlink($include);
            }
        }

        rmdir($dir);
    }

    public function testLoaderWithCacheExists(): void
    {
        $loader = $this->initLoader($this->path . DIRECTORY_SEPARATOR . 'cache', false);
        $template = $loader->load('test::subject.extend_template');

        $names = [];

        foreach ($template->fields as $field) {
            /** @var Field $field */
            $names[] = $field->name();
        }

        self::assertEquals(
            [
                'id',
                'title',
                'content',
            ],
            $names
        );
    }


    public function testLoaderWithCacheNotExists(): void
    {
        $loader = $this->initLoader();
        $template = $loader->load('test::subject.extend_template');

        $this->recursiveRemoveDir(sys_get_temp_dir() . "/cache_test/");

        $names = [];

        foreach ($template->fields as $field) {
            /** @var Field $field */
            $names[] = $field->name();
        }

        self::assertEquals(
            [
                'id',
                'title',
                'content',
            ],
            $names
        );
    }

    public function testLoaderNoCache(): void
    {
        $loader = $this->initLoader();
        $template = $loader->load('test::subject.extend_template', ['without_cache' => true]);

        $names = [];

        foreach ($template->fields as $field) {
            /** @var Field $field */
            $names[] = $field->name();
        }

        self::assertEquals(
            [
                'id',
                'title',
                'content',
            ],
            $names
        );
    }

    public function testLoaderNoCacheRecursive(): void
    {
        $loader = $this->initLoader();
        $this->expectException(InvalidTemplateDefinitionException::class);
        $loader->load('test::subject.extend_template_recursive', ['without_cache' => true]);
    }

    public function testLoaderNoCacheNotExtending(): void
    {
        $loader = $this->initLoader();
        $template = $loader->load('test::subject.extend_template', [
            'without_cache' => true,
            'without_extending' => true,
        ]);

        $names = [];

        foreach ($template->fields as $field) {
            /** @var Field $field */
            $names[] = $field->name();
        }

        self::assertEquals(
            [
                'id',
            ],
            $names
        );
    }
}
