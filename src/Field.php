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
use OpxCore\DataSet\Exceptions\InvalidFieldDefinitionException;
use OpxCore\DataSet\Exceptions\InvalidPropertyTypeException;

/**
 * Class Field
 *
 * @package OpxCore\DataSet
 *
 * @property string $namespace
 * @property string $model
 * @property string $column
 * @property string $label
 * @property string $type
 */
class Field
{
    protected const ATTRIBUTES_LIST = [
        'namespace' => 'string',
        'model' => 'string',
        'column' => 'string',
        'label' => 'string',
        'type' => 'string',
    ];

    /** @var array Attributes */
    protected array $attributes = [];

    /** @var string Field name */
    protected string $name;

    public function __construct(array $field)
    {
        if (empty($field['name'])) {
            throw new InvalidFieldDefinitionException('Field name property must be defined');
        }

        $this->name = $field['name'];
        $this->type = $field['type'] ?? 'default';
    }

    /**
     * Get field name.
     *
     * @return  string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Extend another template by current.
     *
     * @param Field $field
     *
     * @return  void
     */
    public function extendWith(Field $field): void
    {

    }

    public function __get($name)
    {
        if (!isset($this::ATTRIBUTES_LIST[$name])) {
            throw new BadPropertyAccessException("Missing property [{$name}] in Field");
        }

        return $this->attributes[$name] ?? null;
    }

    public function __isset($name)
    {
        return isset($this::ATTRIBUTES_LIST[$name], $this->attributes[$name]);
    }

    public function __set($name, $value)
    {
        if (!isset($this::ATTRIBUTES_LIST[$name])) {
            throw new BadPropertyAccessException("Missing property [{$name}] in Field");
        }
        if (($type = gettype($value)) !== $this::ATTRIBUTES_LIST[$name]) {
            throw new InvalidPropertyTypeException("Invalid type of property [{$name}]. [{$this::ATTRIBUTES_LIST[$name]}] expected, got [{$type}]");
        }
        $this->attributes[$name] = $value;
    }
}