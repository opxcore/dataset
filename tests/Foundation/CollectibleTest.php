<?php
/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Tests\DataSet\Foundation;

use OpxCore\DataSet\Exceptions\InvalidDefinitionException;
use OpxCore\DataSet\Foundation\Collectible;
use PHPUnit\Framework\TestCase;

class CollectibleTest extends TestCase
{
    protected function makeCollectible(
        string $name = 'collectible',
        ?string $label = 'namespace::localization.model.context.collectible',
        ?string $caption = 'caption',
        $permissions = ['permission' => 'test'],
        ?string $namespace = 'namespace',
        ?string $localization = 'localization',
        ?string $model = 'model',
        ?string $context = 'context'
    ): Collectible
    {
        return new Collectible([
            'name' => $name,
            'label' => $label,
            'caption' => $caption,
            'permissions' => $permissions,
        ], $namespace, $localization, $model, $context);
    }

    public function testName(): void
    {
        $collectible = $this->makeCollectible('collectible');
        self::assertEquals('collectible', $collectible->name());
    }

    public function testNoNameGiven(): void
    {
        $this->expectException(InvalidDefinitionException::class);
        new Collectible([]);
    }

    public function testEmptyNameGiven(): void
    {
        $this->expectException(InvalidDefinitionException::class);
        new Collectible(['name' => '']);
    }

    public function testSetLabel(): void
    {
        $collectible = $this->makeCollectible('collectible');
        self::assertEquals('namespace::localization.model.context.collectible', $collectible->label());
    }

    public function testLabelGeneration(): void
    {
        $collectible = $this->makeCollectible('collectible', null, null, [], null, null, null, null);
        $results = [];
        foreach ([null, 'namespace'] as $namespace) {
            foreach ([null, 'localization'] as $localization) {
                foreach ([null, 'model'] as $model) {
                    foreach ([null, 'context'] as $context) {
                        $collectible->setNamespace($namespace);
                        $collectible->setLocalization($localization);
                        $collectible->setModel($model);
                        $collectible->setContext($context);
                        $results[] = $collectible->label();
                    }
                }
            }
        }

        self::assertEquals([
            'collectible',
            'context.collectible',
            'model.collectible',
            'model.context.collectible',
            'localization.collectible',
            'localization.context.collectible',
            'localization.model.collectible',
            'localization.model.context.collectible',
            'namespace::collectible',
            'namespace::context.collectible',
            'namespace::model.collectible',
            'namespace::model.context.collectible',
            'namespace::localization.collectible',
            'namespace::localization.context.collectible',
            'namespace::localization.model.collectible',
            'namespace::localization.model.context.collectible',
        ], $results);
    }

    public function testNamespace(): void
    {
        $collectible = $this->makeCollectible();
        self::assertEquals('namespace', $collectible->namespace());
    }

    public function testLocalization(): void
    {
        $collectible = $this->makeCollectible();
        self::assertEquals('localization', $collectible->localization());
    }

    public function testModel(): void
    {
        $collectible = $this->makeCollectible();
        self::assertEquals('model', $collectible->model());
    }

    public function testContext(): void
    {
        $collectible = $this->makeCollectible();
        self::assertEquals('context', $collectible->context());
    }

    public function testCaption(): void
    {
        $collectible = $this->makeCollectible();
        $collectible->setCaption('new caption');
        self::assertEquals('new caption', $collectible->caption());
    }
}
