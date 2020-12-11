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

use OpxCore\DataSet\Loader\Parser\YamlParser;
use OpxCore\DataSet\Loader\Reader\YamlFileReader;
use OpxCore\DataSet\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    protected function getTemplateArray(): array
    {
        $reader = new YamlFileReader();
        $path = realpath(__DIR__ . '/../src');
        $file = $reader->find('template', 'yaml', $path);

        return (new YamlParser())->parse($reader->content($file));
    }

    /**
     * EXTENDING
     */

    public function testTemplateExtends(): void
    {
        $templateArray = $this->getTemplateArray();
        $template = new Template($templateArray);
        self::assertEquals('namespace::model.template', $template->extends());
    }

    public function testTemplateEmptyExtends(): void
    {
        $templateArray = $this->getTemplateArray();
        unset($templateArray['extends']);
        $template = new Template($templateArray);
        self::assertNull($template->extends());
    }

    public function testTemplateExtendsGlobal(): void
    {
        $templateArray = $this->getTemplateArray();
        $templateArray['extends'] = 'model.template';
        $template = new Template($templateArray);
        self::assertEquals('*::model.template', $template->extends());
    }

    /**
     * FLAGS
     */

    public function testTemplateFlagsNotSet(): void
    {
        $templateArray = $this->getTemplateArray();
        unset($templateArray['flags']);
        $template = new Template($templateArray);
        self::assertTrue($template->isCacheEnabled() && $template->isExtendingEnabled());
    }

    public function testTemplateEmptyFlags(): void
    {
        $templateArray = $this->getTemplateArray();
        $templateArray['flags'] = [];
        $template = new Template($templateArray);
        self::assertTrue($template->isCacheEnabled() === true && $template->isExtendingEnabled() === true);
    }

    public function testTemplateOnlyCacheDisable(): void
    {
        $templateArray = $this->getTemplateArray();
        $templateArray['flags'] = ['disable cache'];
        $template = new Template($templateArray);
        self::assertTrue($template->isCacheEnabled() === false && $template->isExtendingEnabled() === true);
    }

    public function testTemplateOnlyExtendingDisable(): void
    {
        $templateArray = $this->getTemplateArray();
        $templateArray['flags'] = ['disable extending'];
        $template = new Template($templateArray);
        self::assertTrue($template->isCacheEnabled() === true && $template->isExtendingEnabled() === false);
    }

    public function testTemplateCacheAndExtendingDisable(): void
    {
        $templateArray = $this->getTemplateArray();
        $templateArray['flags'] = ['disable cache', 'disable extending'];
        $template = new Template($templateArray);
        self::assertTrue($template->isCacheEnabled() === false && $template->isExtendingEnabled() === false);
    }

    /**
     * NAMESPACE
     * LOCALIZATION
     * MODEL
     */

    public function testTemplateNLM(): void
    {
        $templateArray = $this->getTemplateArray();
        $template = new Template($templateArray);
        self::assertTrue(
            $template->namespace() === 'namespace'
            && $template->localization() === 'template'
            && $template->model() === 'model'
        );
    }

    public function testTemplateNotSetNLM(): void
    {
        $templateArray = $this->getTemplateArray();
        unset($templateArray['namespace'], $templateArray['localization'], $templateArray['model']);
        $template = new Template($templateArray);
        self::assertTrue(
            $template->namespace() === null
            && $template->localization() === null
            && $template->model() === null
        );
    }
}
