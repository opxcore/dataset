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
        $this->assertEquals('inherit', $policy->mode());
    }

    public function testSetMode(): void
    {
        $policy = new Policy(['permissions' => 'test']);
        $policy->setMode('overwrite');
        $this->assertEquals('overwrite', $policy->mode());
    }
}
