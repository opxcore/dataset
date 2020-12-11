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

use OpxCore\DataSet\Foundation\Policy;
use PHPUnit\Framework\TestCase;

class PolicyTest extends TestCase
{
    public function testCreateEmptyPermissions(): void
    {
        $policy = new Policy();
        self::assertNull($policy->permissions());
    }

    public function testCreateNonEmptyPermissions(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        self::assertEquals('test', $policy->permissions());
    }

    public function testSetEmptyPermissions(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setPermissions(null);
        self::assertNull($policy->permissions());
    }

    public function testSetNonEmptyPermissions(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setPermissions('permission');
        self::assertEquals('permission', $policy->permissions());
    }

    public function testSetEmptyInherited(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setInherited(null);
        self::assertNull($policy->inherited()->permissions());
    }

    public function testSetNonEmptyRawInherited(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setInherited(['permissions' => 'permission']);
        self::assertEquals('permission', $policy->inherited()->permissions());
    }

    public function testSetNonEmptyInherited(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $inherited = new Policy(['permissions' => 'permission']);
        $policy->setInherited($inherited);
        self::assertEquals('permission', $policy->inherited()->permissions());
    }

    public function testDefaultMode(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        self::assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
    }

    public function testSetMode(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setMode(null);
        self::assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
        $policy->setMode(Policy::MODE_UNSET);
        self::assertEquals(Policy::MODE_UNSET, $policy->mode());
        $policy->setMode('unset');
        self::assertEquals(Policy::MODE_UNSET, $policy->mode());
        $policy->setMode('no inherit');
        self::assertEquals(Policy::MODE_NO_INHERIT, $policy->mode());
        $policy->setMode('inherit current');
        self::assertEquals(Policy::MODE_INHERIT_CURRENT, $policy->mode());
        $policy->setMode('inherit all');
        self::assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
        $policy->setMode('');
        self::assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
    }

    public function testCollect(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setInherited(new Policy(['permissions' => 'inherited']));

        self::assertEquals([], $policy->collect(Policy::MODE_UNSET));
        self::assertEquals(['test'], $policy->collect(Policy::MODE_NO_INHERIT));
        self::assertEquals(['test'], $policy->collect(Policy::MODE_INHERIT_CURRENT));
        self::assertEquals(['test', 'inherited'], $policy->collect(Policy::MODE_INHERIT_ALL));
    }
}
