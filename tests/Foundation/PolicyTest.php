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
        $this->assertNull($policy->permissions());
    }

    public function testCreateNonEmptyPermissions(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $this->assertEquals('test', $policy->permissions());
    }

    public function testSetEmptyPermissions(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setPermissions(null);
        $this->assertNull($policy->permissions());
    }

    public function testSetNonEmptyPermissions(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setPermissions('permission');
        $this->assertEquals('permission', $policy->permissions());
    }

    public function testSetEmptyInherited(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setInherited(null);
        $this->assertNull($policy->inherited()->permissions());
    }

    public function testSetNonEmptyRawInherited(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setInherited(['permissions' => 'permission']);
        $this->assertEquals('permission', $policy->inherited()->permissions());
    }

    public function testSetNonEmptyInherited(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $inherited = new Policy(['permissions' => 'permission']);
        $policy->setInherited($inherited);
        $this->assertEquals('permission', $policy->inherited()->permissions());
    }

    public function testDefaultMode(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $this->assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
    }

    public function testSetMode(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setMode(null);
        $this->assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
        $policy->setMode(Policy::MODE_UNSET);
        $this->assertEquals(Policy::MODE_UNSET, $policy->mode());
        $policy->setMode('unset');
        $this->assertEquals(Policy::MODE_UNSET, $policy->mode());
        $policy->setMode('no inherit');
        $this->assertEquals(Policy::MODE_NO_INHERIT, $policy->mode());
        $policy->setMode('inherit current');
        $this->assertEquals(Policy::MODE_INHERIT_CURRENT, $policy->mode());
        $policy->setMode('inherit all');
        $this->assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
        $policy->setMode('');
        $this->assertEquals(Policy::MODE_INHERIT_ALL, $policy->mode());
    }

    public function testCollect(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setInherited(new Policy(['permissions' => 'inherited']));

        $this->assertEquals([], $policy->collect(Policy::MODE_UNSET));
        $this->assertEquals(['test'], $policy->collect(Policy::MODE_NO_INHERIT));
        $this->assertEquals(['test'], $policy->collect(Policy::MODE_INHERIT_CURRENT));
        $this->assertEquals(['test', 'inherited'], $policy->collect(Policy::MODE_INHERIT_ALL));
    }
}
