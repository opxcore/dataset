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
use ArrayIterator;
use IteratorAggregate;
use InvalidArgumentException;
use OpxCore\DataSet\Traits\TemplateGeneralPropertiesTrait;

class Template implements ArrayAccess, IteratorAggregate
{
    use TemplateGeneralPropertiesTrait;

    /** @var array|null Set of sections */
    protected ?array $sections = null;

    /** @var array|null Set of groups */
    protected ?array $groups = null;

    /** @var array|null Set of fields */
    protected ?array $fields = null;

    public function __construct(?array $template = null)
    {
        $this->setExtends($template['extends'] ?? null);
        $this->setFlags($template['flags'] ?? []);
        $this->setNamespace($template['namespace'] ?? null);
        $this->setLocalization($template['localization'] ?? null);
        $this->setModel($template['model'] ?? null);

        //$template['sections']
        //$template['groups']
        if (isset($template['fields']) && is_array($template['fields'])) {
            foreach ($template['fields'] as $field) {
                $this->addField(new Field($field));
            }
        }
    }


    /**
     * Extend another template by current.
     *
     * @param Template $template
     *
     * @return  void
     */
    public function extendWith(Template $template): void
    {
        $fields = $template->fields();

        foreach ($fields as $name => $field) {
            /** @var Field $field */
            if (isset($this[$name])) {
                $this[$name]->extendWith($field);
                continue;
            }

            $this->addField($field);
        }
    }

    /**
     * Add field to template.
     *
     * @param Field $field
     *
     * @return  void
     */
    public function addField(Field $field): void
    {
        $this[$field->name()] = $field;
    }

    /**
     * Get all fields.
     *
     * @return  array
     */
    public function fields(): array
    {
        return $this->fields;
    }

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

    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }
}