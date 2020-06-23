<?php

namespace OpxCore\Tests\DataSet;

use InvalidArgumentException;
use OpxCore\DataSet\Template;
use OpxCore\DataSet\Field;
use PHPUnit\Framework\TestCase;

class TemplateArrayAccessTest extends TestCase
{

    public function testOffsetExists(): void
    {
        $template = new Template();
        $field = new Field();
        $template['test'] = $field;
        $this->assertTrue(isset($template['test']));
    }

    public function testOffsetNotExists(): void
    {
        $template = new Template();
        $field = new Field();
        $template['test'] = $field;
        $this->assertFalse(isset($template['not_test']));
    }

    public function testOffsetInvalidSet(): void
    {
        $template = new Template();
        $this->expectException(InvalidArgumentException::class);
        $template['test'] = ['test'];
        unset($template);
    }

    public function testOffsetGet(): void
    {
        $template = new Template();
        $field = new Field();
        $template['test'] = $field;
        $this->assertEquals($template['test'], $field);
    }

    public function testOffsetUnset(): void
    {
        $template = new Template();
        $field = new Field();
        $template['test'] = $field;
        unset($template['test']);
        $this->assertFalse(isset($template['test']));
    }
}
