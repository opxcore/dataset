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

use ArrayAccess;
use InvalidArgumentException;

class Template implements ArrayAccess
{
    /** @var array|null Set of sections */
    protected ?array $sections;

    /** @var array|null Set of groups */
    protected ?array $groups;

    /** @var array|null Set of fields */
    protected ?array $fields;

    /**
     * Whether a field exists.
     *
     * @param string $fieldName A name of field to check for.
     *
     * @return  bool
     */
    public function offsetExists($fieldName): bool
    {
        return isset($this->fields[$fieldName]);
    }

    /**
     * Retrieve field.
     *
     * @param string $fieldName
     *
     * @return  Field
     */
    public function offsetGet($fieldName): Field
    {
        return $this->fields[$fieldName];
    }

    /**
     * Set field.
     *
     * @param string $fieldName
     * @param Field $field
     *
     * @return  void
     */
    public function offsetSet($fieldName, $field): void
    {
        if (!$field instanceof Field) {
            $type = gettype($field);
            $type = ($type !== 'object') ?: get_class($field);
            throw new InvalidArgumentException("New value must be type of [TemplateField], [{$type}] given.");
        }

        $this->fields[$fieldName] = $field;
    }

    /**
     * Unset field.
     *
     * @param mixed $fieldName
     *
     * @return  void
     */
    public function offsetUnset($fieldName): void
    {
        unset($this->fields[$fieldName]);
    }
}