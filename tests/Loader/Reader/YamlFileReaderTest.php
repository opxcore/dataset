<?php

namespace OpxCore\Tests\DataSet\Loader\Reader;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\Exceptions\FileNotFoundException;
use OpxCore\DataSet\Loader\Exceptions\FileReadErrorException;
use OpxCore\DataSet\Loader\Reader\YamlFileReader;
use OpxCore\DataSet\Loader\File;
use PHPUnit\Framework\TestCase;

class YamlFileReaderTest extends TestCase
{
    protected string $path = __DIR__;

    public function testFindFileSinglePathExists(): void
    {
        $loader = new YamlFileReader;
        $templateFile = $loader->find('/assets/simple_test', null, $this->path);

        $this->assertEquals(
            'simple_test',
            $templateFile->filename()
        );
    }

    public function testFindFileMultiplePathExists(): void
    {
        $loader = new YamlFileReader;
        $templateFile = $loader->find('/assets/simple_test', 'ext', ['some_path', $this->path, 'another_path']);

        $this->assertEquals(
            'simple_test',
            $templateFile->filename()
        );
    }

    public function testFindFileNotExists(): void
    {
        $loader = new YamlFileReader;

        $this->expectException(FileNotFoundException::class);

        $loader->find('/assets/simple_test', null, ['some_path', 'another_path']);
    }

    public function testGetFileContent(): void
    {
        $loader = new YamlFileReader;
        $templateFile = $loader->find('/assets/simple_test', 'ext', ['some_path', $this->path, 'another_path']);

        $this->assertEquals(
            'test',
            $loader->content($templateFile)
        );
    }

    public function testGetFileContentError(): void
    {
        $loader = new YamlFileReader;
        $templateFile = $loader->find('simple_test', null, ['some_path', $this->path . '/assets', 'another_path']);
        $templateIllegalFile = new File($templateFile->filename(), '.wrong', $templateFile->path(), new Carbon);

        $this->expectException(FileReadErrorException::class);

        $loader->content($templateIllegalFile);
    }
}
