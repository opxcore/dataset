<?php
/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Tests\DataSet\Foundation;

use InvalidArgumentException;
use OpxCore\DataSet\Foundation\Collectible;
use OpxCore\DataSet\Foundation\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    protected function makeCollection(): Collection
    {
        return new Collection();
    }

    protected function makeCollectible(string $name): Collectible
    {
        return new Collectible(['name' => $name]);
    }

    public function testOffsetExists(): void
    {
        $collection = $this->makeCollection();
        $collectible = $this->makeCollectible('test');
        $collection->add($collectible);
        self::assertTrue(isset($collection['test']));
    }

    public function testOffsetNotExists(): void
    {
        $collection = $this->makeCollection();
        $collectible = $this->makeCollectible('test');
        $collection->add($collectible);
        self::assertFalse(isset($collection['not_test']));
    }

    public function testOffsetInvalidSet(): void
    {
        $collection = $this->makeCollection();
        $this->expectException(InvalidArgumentException::class);
        $collection['test'] = ['test'];
        unset($collection);
    }

    public function testOffsetGet(): void
    {
        $collection = $this->makeCollection();
        $collectible = $this->makeCollectible('test');
        $collection->add($collectible);
        self::assertEquals($collection['test'], $collectible);
    }

    public function testOffsetUnset(): void
    {
        $collection = $this->makeCollection();
        $collectible = $this->makeCollectible('test');
        $collection->add($collectible);
        unset($collection['test']);
        self::assertFalse(isset($collection['test']));
    }
}
