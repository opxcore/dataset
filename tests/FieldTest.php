<?php
/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Tests\DataSet;

use OpxCore\DataSet\Exceptions\BadPropertyAccessException;
use OpxCore\DataSet\Exceptions\InvalidPropertyTypeException;
use OpxCore\DataSet\Field;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    protected function makeField(?string $type = null, ?string $namespace = null, ?string $localization = null, ?string $model = null): Field
    {
        $field = ['name' => 'test'];
        if ($type !== null) {
            $field['type'] = $type;
        }
        return new Field($field, $namespace, $localization, $model);
    }

    public function testCreateFromArray(): void
    {
        $field = $this->makeField();
        self::assertEquals(
            'default',
            $field->type
        );
    }

    public function testCreateFromArrayWithType(): void
    {
        $field = $this->makeField('string');
        self::assertEquals(
            'string',
            $field->type
        );
    }

    public function testGetWrong(): void
    {
        $field = $this->makeField();
        $this->expectException(BadPropertyAccessException::class);
        $field->ddff;
    }

    public function testSet(): void
    {
        $field = $this->makeField();
        $field->type = 'testing';
        self::assertEquals(
            'testing',
            $field->type
        );
    }

    public function testIsSet(): void
    {
        $field = $this->makeField();
        $field->validation = 'testing';
        self::assertTrue(isset($field->validation));
    }

    public function testIsNotSet(): void
    {
        $field = $this->makeField();
        self::assertFalse(isset($field->validation));
    }

    public function testSetInvalidProperty(): void
    {
        $field = $this->makeField();
        $this->expectException(BadPropertyAccessException::class);
        $field->invalid_property = 'test';
    }

    public function testGetInvalidProperty(): void
    {
        $field = $this->makeField();
        $this->expectException(BadPropertyAccessException::class);
        $field->invalid_property;
    }

    public function testInvalidPropertyType(): void
    {
        $field = $this->makeField();
        $this->expectException(InvalidPropertyTypeException::class);
        $field->validation = ['wrong'];
    }
}
