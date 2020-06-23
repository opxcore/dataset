<?php

namespace OpxCore\Tests\DataSet\Loader;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    protected function initFile(): File
    {
        return new File('filename', 'ext', 'path', Carbon::parse('2020-06-18 12:00'));
    }

    public function testGetFileName(): void
    {
        $file = $this->initFile();
        $this->assertEquals('filename.ext', $file->getFileName());
    }

    public function testGetFilePath(): void
    {
        $file = $this->initFile();
        $this->assertEquals('path', $file->getFilePath());
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
        $this->assertTrue($file->isNotNewerWhen(Carbon::parse('2020-06-18 12:00')));
    }
}
