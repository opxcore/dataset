<?php

namespace OpxCore\Tests\DataSet\Loader\Reader;

use Carbon\Carbon;
use OpxCore\DataSet\Loader\Exceptions\FileNotFoundException;
use OpxCore\DataSet\Loader\Exceptions\FileReadErrorException;
use OpxCore\DataSet\Loader\Reader\FileContentReader;
use OpxCore\DataSet\Loader\File;
use PHPUnit\Framework\TestCase;

class FileContentReaderTest extends TestCase
{
    protected string $assetsPath = __DIR__;

    public function testFindFileSinglePathExists(): void
    {
        $loader = new FileContentReader;
        $templateFile = $loader->find('/assets/simple_test', null, $this->assetsPath);

        $this->assertEquals(
            '/assets/simple_test',
            $templateFile->getFileName()
        );
    }

    public function testFindFileMultiplePathExists(): void
    {
        $loader = new FileContentReader;
        $templateFile = $loader->find('/assets/simple_test', 'ext', ['some_path', $this->assetsPath, 'another_path']);

        $this->assertEquals(
            '/assets/simple_test.ext',
            $templateFile->getFileName()
        );
    }

    public function testFindFileNotExists(): void
    {
        $loader = new FileContentReader;

        $this->expectException(FileNotFoundException::class);

        $loader->find('/assets/simple_test', null, ['some_path', 'another_path']);
    }

    public function testGetFileContent(): void
    {
        $loader = new FileContentReader;
        $templateFile = $loader->find('/assets/simple_test', 'ext', ['some_path', $this->assetsPath, 'another_path']);

        $this->assertEquals(
            'test',
            $loader->content($templateFile)
        );
    }

    public function testGetFileContentError(): void
    {
        $loader = new FileContentReader;
        $templateFile = $loader->find('/assets/simple_test', null, ['some_path', $this->assetsPath, 'another_path']);
        $templateIllegalFile = new File($templateFile->getFileName(), '.wrong', $templateFile->getFilePath(), new Carbon);

        $this->expectException(FileReadErrorException::class);

        $loader->content($templateIllegalFile);
    }
}
