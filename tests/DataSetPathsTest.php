<?php

namespace OpxCore\Tests\DataSet;

use OpxCore\PathSet\Exceptions\PathNotExistsException;
use OpxCore\DataSet\DataSet;
use PHPUnit\Framework\TestCase;

class DataSetPathsTest extends TestCase
{
    protected function setUp(): void
    {
        DataSet::addPath('*', 'global');
        DataSet::addPath('*', 'global_alt');
        DataSet::addPath('name', 'local');
        DataSet::addPath('name', 'local_alt');
    }

    protected function tearDown(): void
    {
        DataSet::clearPaths();
    }

    public function testAddNewPath(): void
    {
        DataSet::addPath('another', 'path');

        $this->assertEquals(
            [
                'path',
                'global_alt',
                'global',
            ],
            DataSet::getPaths('another')
        );
    }

    public function testAddAlternate(): void
    {
        DataSet::addPath('name', 'path');

        $this->assertEquals(
            [
                'path',
                'local_alt',
                'local',
                'global_alt',
                'global',
            ],
            DataSet::getPaths('name')
        );
    }

    public function testGetWrongPath(): void
    {
        $this->expectException(PathNotExistsException::class);

        DataSet::getPaths('another');
    }

    public function testGetPathSet(): void
    {
        $pathSet = DataSet::getPaths();

        $this->assertEquals(
            [
                'global_alt',
                'global',
            ],
            $pathSet->get()
        );
    }
}
