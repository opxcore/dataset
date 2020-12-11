<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Foundation;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * Base collection of sections, groups and fields.
 *
 */
class Collection implements ArrayAccess, IteratorAggregate, Countable
{
    /** @var array|null Set of Collectibles in collection */
    protected ?array $contents = null;

    /**
     * Add collectible.
     *
     * @param Collectible $collectible
     *
     * @return  void
     */
    public function add(Collectible $collectible): void
    {
        $this[$collectible->name()] = $collectible;
    }

    /**
     * Whether a Collectible exists.
     *
     * @param mixed $offset A name of Collectible to check for.
     *
     * @return  bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->contents[$offset]);
    }

    /**
     * Retrieve Collectible.
     *
     * @param mixed $offset
     *
     * @return  Collectible
     */
    public function offsetGet($offset): Collectible
    {
        return $this->contents[$offset];
    }

    /**
     * Set Collectible.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return  void
     */
    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof Collectible) {
            $type = gettype($value);
            $type = ($type !== 'object') ?: get_class($value);
            throw new InvalidArgumentException("New value must be an instance of [Collectible], [{$type}] given.");
        }

        $this->contents[$offset] = $value;
    }

    /**
     * Unset Collectible.
     *
     * @param mixed $offset
     *
     * @return  void
     */
    public function offsetUnset($offset): void
    {
        unset($this->contents[$offset]);
    }

    /**
     * Gel content iterator.
     *
     * @return  ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->contents);
    }

    /**
     * Count collectibles.
     *
     * @return  int
     */
    public function count(): int
    {
        return $this->contents === null ? 0 : count($this->contents);
    }
}