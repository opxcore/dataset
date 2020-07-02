<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet;

use OpxCore\DataSet\Exceptions\BadPropertyAccessException;
use OpxCore\DataSet\Exceptions\InvalidPropertyTypeException;
use OpxCore\DataSet\Foundation\Collectible;
use OpxCore\Interfaces\Authorization\AuthorizationResponse;

/**
 * Class Field
 *
 * @package OpxCore\DataSet
 *
 * @property string|null $type
 * @property string|null $validation
 * @property string|null $section
 * @property string|null $group
 */
class Field extends Collectible
{
    /**
     * List of enabled attributes.
     */
    protected const ATTRIBUTES_LIST = [
        'type' => 'string',
        'section' => 'string|null',
        'group' => 'string|null',
        'validation' => 'string',
    ];

    /** @var array Attributes */
    protected array $attributes = [];

    /** @var bool|null Whether field can be read. */
    protected ?bool $read = null;

    /** @var bool|null Whether field can be updated. */
    protected ?bool $update = null;

    /**
     * Field constructor.
     *
     * @param array $properties Array of base properties definition. `name` is required.
     * @param string $context
     * @param string|null $namespace
     * @param string|null $localization
     * @param string|null $model
     *
     * @return  void
     */
    public function __construct(array $properties, ?string $namespace = null, ?string $localization = null, ?string $model = null, string $context = 'field')
    {
        parent::__construct($properties, $namespace, $localization, $model, $context);

        $this->type = $properties['type'] ?? 'default';

        if (isset($properties['placement'])) {
            $exploded = explode('/', $properties['placement']);
            $section = empty($exploded[0]) ? null : $exploded[0];
            $group = empty($exploded[1]) ? null : $exploded[1];
        } else {
            $section = $properties['section'] ?? null;
            $group = $properties['group'] ?? null;
        }
        $this->section = $section;
        $this->group = $group;
    }

    /**
     * Extend another template by current.
     *
     * @param Collectible $collectible
     *
     * @return  void
     */
    public function extend(Collectible $collectible): void
    {
        parent::extend($collectible);
        // TODO: add field overriding
    }

    /**
     * Apply authorization.
     *
     * @param AuthorizationResponse $response
     *
     * @return  void
     */
    public function authorize(AuthorizationResponse $response): void
    {
        $this->read = $response->can('read');
        $this->update = $response->can('update');
    }

    /**
     * Whether field could bu read.
     *
     * @return  bool
     */
    public function couldBeRead(): bool
    {
        return $this->read ?? true;
    }

    /**
     * Whether field could bu updated.
     *
     * @return  bool
     */
    public function couldBeUpdated(): bool
    {
        return $this->update ?? true;
    }

    /**
     * Attribute getter.
     *
     * @param string $name
     *
     * @return  mixed|null
     */
    public function __get($name)
    {
        if (!isset($this::ATTRIBUTES_LIST[$name])) {
            throw new BadPropertyAccessException("Missing property [{$name}] in Field");
        }

        return $this->attributes[$name] ?? null;
    }

    /**
     * Whether attribute set.
     *
     * @param string $name
     *
     * @return  bool
     */
    public function __isset($name)
    {
        return isset($this::ATTRIBUTES_LIST[$name], $this->attributes[$name]);
    }

    /**
     * Attribute setter.
     *
     * @param mixed $name
     * @param mixed $value
     *
     * @return  void
     */
    public function __set($name, $value)
    {
        if (!isset($this::ATTRIBUTES_LIST[$name])) {
            throw new BadPropertyAccessException("Missing property [{$name}] in Field");
        }
        $type = strtolower(gettype($value));
        $expected = explode('|', $this::ATTRIBUTES_LIST[$name]);
        if (!in_array($type, $expected, true)) {
            throw new InvalidPropertyTypeException("Invalid type of property [{$name}]. [{$this::ATTRIBUTES_LIST[$name]}] expected, got [{$type}]");
        }
        $this->attributes[$name] = $value;
    }
}