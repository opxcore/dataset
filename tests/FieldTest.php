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
use OpxCore\DataSet\Exceptions\InvalidFieldDefinitionException;
use OpxCore\DataSet\Exceptions\InvalidPropertyTypeException;
use OpxCore\DataSet\Field;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    protected function makeField(): Field
    {
        return new Field(['name' => 'test']);
    }

    public function testCreateFromArray(): void
    {
        $field = $this->makeField();
        $this->assertEquals(
            ['test', 'default'],
            [$field->name(), $field->type]
        );
    }

    public function testCreateWrong(): void
    {
        $this->expectException(InvalidFieldDefinitionException::class);
        $field = new Field(['name' => '']);
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
        $field->namespace = 'testing';
        $this->assertEquals(
            'testing',
            $field->namespace
        );
    }

    public function testIsSet(): void
    {
        $field = $this->makeField();
        $field->namespace = 'testing';
        $this->assertTrue(isset($field->namespace));
    }

    public function testIsNotSet(): void
    {
        $field = $this->makeField();
        $this->assertFalse(isset($field->namespace));
    }

    public function testBadProperty(): void
    {
        $field = $this->makeField();
        $this->expectException(BadPropertyAccessException::class);
        $field->ddff = 'ssss';
    }

    public function testInvalidPropertyType(): void
    {
        $field = $this->makeField();
        $this->expectException(InvalidPropertyTypeException::class);
        $field->label = ['wrong'];
    }
}
