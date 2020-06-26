<?php

namespace OpxCore\Tests\DataSet;

use InvalidArgumentException;
use OpxCore\DataSet\Template;
use OpxCore\DataSet\Field;
use PHPUnit\Framework\TestCase;

class TemplateArrayAccessTest extends TestCase
{
    protected function makeTemplate(): Template
    {
        return new Template();
    }

    protected function makeField(string $name): Field
    {
        return new Field(['name' => $name]);
    }

    public function testOffsetExists(): void
    {
        $template = $this->makeTemplate();
        $field = $this->makeField('test');
        $template->addField($field);
        $this->assertTrue(isset($template['test']));
    }

    public function testOffsetNotExists(): void
    {
        $template = $this->makeTemplate();
        $field = $this->makeField('test');
        $template->addField($field);
        $this->assertFalse(isset($template['not_test']));
    }

    public function testOffsetInvalidSet(): void
    {
        $template = $this->makeTemplate();
        $this->expectException(InvalidArgumentException::class);
        $template['test'] = ['test'];
        unset($template);
    }

    public function testOffsetGet(): void
    {
        $template = $this->makeTemplate();
        $field = $this->makeField('test');
        $template->addField($field);
        $this->assertEquals($template['test'], $field);
    }

    public function testOffsetUnset(): void
    {
        $template = new Template();
        $field = $this->makeField('test');
        $template->addField($field);
        unset($template['test']);
        $this->assertFalse(isset($template['test']));
    }
}
