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
     * @param string $name A name of Collectible to check for.
     *
     * @return  bool
     */
    public function offsetExists($name): bool
    {
        return isset($this->contents[$name]);
    }

    /**
     * Retrieve Collectible.
     *
     * @param string $name
     *
     * @return  Collectible
     */
    public function offsetGet($name): Collectible
    {
        return $this->contents[$name];
    }

    /**
     * Set Collectible.
     *
     * @param string $name
     * @param Collectible $collectible
     *
     * @return  void
     */
    public function offsetSet($name, $collectible): void
    {
        if (!$collectible instanceof Collectible) {
            $type = gettype($collectible);
            $type = ($type !== 'object') ?: get_class($collectible);
            throw new InvalidArgumentException("New value must be an instance of [Collectible], [{$type}] given.");
        }

        $this->contents[$name] = $collectible;
    }

    /**
     * Unset Collectible.
     *
     * @param mixed $name
     *
     * @return  void
     */
    public function offsetUnset($name): void
    {
        unset($this->contents[$name]);
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