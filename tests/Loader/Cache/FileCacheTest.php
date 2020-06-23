<?php

namespace OpxCore\Tests\DataSet\Loader\Cache;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\Cache\FileCache;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    protected $path = __DIR__;

    public function testCacheHasContent(): void
    {
        $cache = new FileCache($this->path . '/assets/');
        $cache->set('test', 'test');
        $this->assertTrue($cache->has('test', (new Carbon)->addHours(-1)));
        $cache->unset('test');
    }

    public function testCacheHasOutdatedContent(): void
    {
        $cache = new FileCache($this->path . '/assets/');
        $cache->set('test', 'test');
        $this->assertFalse($cache->has('test', (new Carbon)->addHours(1)));
        $cache->unset('test');
    }

    public function testCacheMissing(): void
    {
        $cache = new FileCache($this->path . '/assets/');
        $this->assertFalse($cache->has('missing', (new Carbon)->addHours(1)));
    }

    public function testCacheGetContent(): void
    {
        $cache = new FileCache($this->path . '/assets/');
        $cache->set('test', 'test');
        $this->assertEquals('test', $cache->get('test'));
        $cache->unset('test');
    }
}
