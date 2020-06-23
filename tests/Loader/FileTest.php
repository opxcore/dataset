<?php

namespace OpxCore\Tests\DataSet\Loader;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    protected function initFile(): File
    {
        return new File('sub/filename', 'ext', 'path/home', Carbon::parse('2020-06-18 12:00'));
    }

    public function testGetFileName(): void
    {
        $file = $this->initFile();
        $this->assertEquals(
            ['path/home', 'sub', 'filename', 'ext', Carbon::parse('2020-06-18 12:00')],
            [$file->path(), $file->localPath(), $file->filename(), $file->extension(), $file->timestamp()]
        );
    }

    public function testFileNewerCase(): void
    {
        $file = $this->initFile();
        $this->assertFalse($file->isNotNewerWhen(Carbon::parse('2020-06-18 11:59')));
    }

    public function testFileOlderCase(): void
    {
        $file = $this->initFile();
        $this->assertTrue($file->isNotNewerWhen(Carbon::parse('2020-06-18 12:01')));
    }

    public function testFileSameCase(): void
    {
        $file = $this->initFile();
        $file->setTimestamp(Carbon::parse('2020-06-18 12:00'));
        $this->assertTrue($file->isNotNewerWhen(Carbon::parse('2020-06-18 12:00')));
    }
}
