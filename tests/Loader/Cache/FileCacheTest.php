<?php

namespace OpxCore\Tests\DataSet\Loader\Cache;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\Cache\FileCache;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    protected function makeCacheDriver(): FileCache
    {
        return new FileCache(sys_get_temp_dir());
    }

    public function testCacheHasContent(): void
    {
        $cache = $this->makeCacheDriver();
        $cache->set('test', 'test');
        self::assertTrue($cache->has('test', (new Carbon)->addHours(-1)));
        $cache->unset('test');
    }

    public function testCacheHasOutdatedContent(): void
    {
        $cache = $this->makeCacheDriver();
        $cache->set('test', 'test');
        self::assertFalse($cache->has('test', (new Carbon)->addHours(1)));
        $cache->unset('test');
    }

    public function testCacheMissing(): void
    {
        $cache = $this->makeCacheDriver();
        self::assertFalse($cache->has('missing', (new Carbon)->addHours(1)));
    }

    public function testCacheGetContent(): void
    {
        $cache = $this->makeCacheDriver();
        $cache->set('test', 'test');
        self::assertEquals('test', $cache->get('test'));
        $cache->unset('test');
    }
}
