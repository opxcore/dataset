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

use OpxCore\DataSet\Field;
use OpxCore\DataSet\Foundation\Policy;
use OpxCore\DataSet\Group;
use OpxCore\DataSet\Section;
use OpxCore\DataSet\Template;
use Generator;
use OpxCore\Tests\DataSet\Fixtures\Authorization;
use PHPUnit\Framework\TestCase;

class TemplatePermissionsTest extends TestCase
{
    protected function initObjects(): array
    {
        $template = new Template();
        $section = new Section(['name' => 'section']);
        $group = new Group(['name' => 'group']);
        $field = new Field(['name' => 'field', 'placement' => 'section/group']);
        $template->sections['section'] = $section;
        $template->groups['group'] = $group;
        $template->fields['field'] = $field;

        return [$template, $section, $group, $field];
    }

    protected function policyGenerator(int $mode): ?Generator
    {
        $permissions = [
            ['read' => false, 'update' => false],
            ['read' => true, 'update' => true],
        ];
        foreach ($permissions as $permission) {
            $policy = new Policy(['permissions' => $permission, 'mode' => $mode]);
            $policy->setInherited(new Policy([[
                'permissions' => ['read' => false, 'update' => false],
                'mode' => Policy::MODE_INHERIT_ALL
            ]]));
            yield $policy;
        }
    }

    public function testPermissionsUnset(): void
    {
        /** @var Template $template */
        /** @var Section $section */
        /** @var Group $group */
        /** @var Field $field */
        [$template, $section, $group, $field] = $this->initObjects();

        foreach ([$template, $section, $group, $field] as $object) {
            // Reset policies
            $template->policy(new Policy(['permissions' => ['read' => true, 'update' => true], 'mode' => Policy::MODE_UNSET]));
            $template->sections['section']->policy(new Policy(['permissions' => ['read' => true, 'update' => true], 'mode' => Policy::MODE_UNSET]));
            $template->groups['group']->policy(new Policy(['permissions' => ['read' => true, 'update' => true], 'mode' => Policy::MODE_UNSET]));
            $template->fields['field']->policy(new Policy(['permissions' => ['read' => true, 'update' => true], 'mode' => Policy::MODE_UNSET]));
            // Iterate object
            foreach ($this->policyGenerator(Policy::MODE_UNSET) as $policy) {
                $object->policy($policy);
                $template->resolvePermissions(new Authorization());
                self::assertTrue($template->fields['field']->couldBeRead());
                self::assertTrue($template->fields['field']->couldBeUpdated());
            }
        }
    }

    public function testPermissionsNoInherit(): void
    {
        /** @var Template $template */
        /** @var Section $section */
        /** @var Group $group */
        /** @var Field $field */
        [$template] = $this->initObjects();

        foreach ($this->policyGenerator(Policy::MODE_NO_INHERIT) as $templatePolicy) {
            foreach ($this->policyGenerator(Policy::MODE_NO_INHERIT) as $sectionPolicy) {
                foreach ($this->policyGenerator(Policy::MODE_NO_INHERIT) as $groupPolicy) {
                    // Set policies
                    $template->policy($templatePolicy);
                    $template->sections['section']->policy($sectionPolicy);
                    $template->groups['group']->policy($groupPolicy);
                    $template->fields['field']->policy(new Policy(['permissions' => ['read' => false, 'update' => false], 'mode' => Policy::MODE_NO_INHERIT]));
                    $template->resolvePermissions(new Authorization());
                    self::assertFalse($template->fields['field']->couldBeRead());
                    self::assertFalse($template->fields['field']->couldBeUpdated());
                    $template->fields['field']->policy(new Policy(['permissions' => ['read' => true, 'update' => true], 'mode' => Policy::MODE_NO_INHERIT]));
                    $template->resolvePermissions(new Authorization());
                    self::assertTrue($template->fields['field']->couldBeRead());
                    self::assertTrue($template->fields['field']->couldBeUpdated());
                }
            }
        }
    }

    public function testPermissionsInheritCurrent(): void
    {
        /** @var Template $template */
        /** @var Section $section */
        /** @var Group $group */
        /** @var Field $field */
        [$template] = $this->initObjects();

        foreach ($this->policyGenerator(Policy::MODE_INHERIT_CURRENT) as $it => $templatePolicy) {
            foreach ($this->policyGenerator(Policy::MODE_INHERIT_CURRENT) as $is => $sectionPolicy) {
                foreach ($this->policyGenerator(Policy::MODE_INHERIT_CURRENT) as $ig => $groupPolicy) {
                    foreach ($this->policyGenerator(Policy::MODE_INHERIT_CURRENT) as $if => $fieldPolicy) {
                        // Set policies
                        $template->policy($templatePolicy);
                        $template->sections['section']->policy($sectionPolicy);
                        $template->groups['group']->policy($groupPolicy);
                        $template->fields['field']->policy($fieldPolicy);
                        $template->resolvePermissions(new Authorization());

                        $can = $it && $is && $ig && $if;

                        self::assertEquals($can, $template->fields['field']->couldBeRead());
                        self::assertEquals($can, $template->fields['field']->couldBeUpdated());
                    }
                }
            }
        }
    }

    public function testPermissionsInheritAll(): void
    {
        /** @var Template $template */
        /** @var Section $section */
        /** @var Group $group */
        /** @var Field $field */
        [$template] = $this->initObjects();

        $template->policy()->setInherited(new Policy(['permissions' => ['read' => false, 'update' => false], 'mode' => Policy::MODE_INHERIT_ALL]));
        $template->resolvePermissions(new Authorization());
        self::assertFalse($template->fields['field']->couldBeRead());
        self::assertFalse($template->fields['field']->couldBeUpdated());

        $template->policy()->setMode(Policy::MODE_NO_INHERIT);

        $template->resolvePermissions(new Authorization());
        self::assertTrue($template->fields['field']->couldBeRead());
        self::assertTrue($template->fields['field']->couldBeUpdated());
    }
}
