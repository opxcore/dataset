<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet;

use OpxCore\DataSet\Foundation\Collectible;
use OpxCore\DataSet\Foundation\Policy;
use OpxCore\DataSet\Traits\HasNamings;
use OpxCore\DataSet\Traits\TemplateGeneralPropertiesTrait;
use OpxCore\DataSet\Foundation\Collection;
use OpxCore\Interfaces\Authorization\AuthorizationInterface;

class Template
{
    use TemplateGeneralPropertiesTrait,
        HasNamings;

    /** @var Policy Policy to authorize */
    protected Policy $policy;

    /** @var Collection Set of sections */
    public Collection $sections;

    /** @var Collection Set of groups */
    public Collection $groups;

    /** @var Collection Set of fields */
    public Collection $fields;

    public function __construct(?array $template = null)
    {
        $this->setExtends($template['extends'] ?? null);
        $this->setFlags($template['flags'] ?? []);
        $this->setNamespace($template['namespace'] ?? null);
        $this->setLocalization($template['localization'] ?? null);
        $this->setModel($template['model'] ?? null);
        $this->policy = new Policy($template['policy'] ?? null);
        $this->sections = new Collection;
        $this->groups = new Collection;
        $this->fields = new Collection;

        if ($template !== null) {
            $this->addCollectibles($template, 'sections', Section::class);
            $this->addCollectibles($template, 'groups', Group::class);
            $this->addCollectibles($template, 'fields', Field::class);
        }
    }

    /**
     * Iterate and add collectibles.
     *
     * @param array $template
     * @param string $key
     * @param string $collectibleClass
     *
     * @return  void
     */
    protected function addCollectibles(array $template, string $key, string $collectibleClass): void
    {
        if (isset($template[$key]) && is_array($template[$key])) {
            foreach ($template[$key] as $rawCollectible) {
                $collectible = new $collectibleClass($rawCollectible, $this->namespace(), $this->localization(), $this->model(), $key);
                $this->{$key}[$collectible->name()] = $collectible;
            }
        }
    }

    /**
     * Get or set template policy.
     *
     * @param Policy|null $policy
     *
     * @return  Policy
     */
    public function policy(?Policy $policy = null): Policy
    {
        if ($policy !== null) {
            $this->policy = $policy;
        }

        return $this->policy;
    }

    /**
     * Extend another template by current.
     *
     * @param Template $template
     *
     * @return  void
     */
    public function extend(Template $template): void
    {
        $this->policy->setInherited($template->policy());

        foreach (['sections', 'groups', 'fields'] as $key) {
            if ($template->{$key}->count() === 0) {
                continue;
            }
            /** @var Collectible $collectibles */
            $collectibles = $template->{$key};

            foreach ($collectibles as $name => $collectible) {
                /** @var Collectible $collectible */
                if (isset($this->{$key}[$name])) {
                    $this->{$key}[$name]->extend($collectible);
                    continue;
                }
                $this->{$key}->add($collectible);
            }
        }
    }

    /**
     * Collect applicable permissions for field.
     *
     * @param Field $field Field to collect permissions.
     *
     * @return  array|null
     */
    protected function collectFieldPermissions(Field $field): ?array
    {
        $policy = $field->policy();
        $mode = $policy->mode();
        $collection = [$policy->collect($mode)];

        // Get only one field permission or unset all permissions
        switch ($mode) {
            case Policy::MODE_UNSET:
            case Policy::MODE_NO_INHERIT:
                // There is no need to collect other permissions.
                break;

            // Get all or only current template permission inheriting
            case Policy::MODE_INHERIT_CURRENT:
            case Policy::MODE_INHERIT_ALL:
            default:
                $collection[] = $this->policy->collect($mode);

                if (isset($field->section, $this->sections[$field->section])) {
                    /** @var Section $section */
                    $section = $this->sections[$field->section];
                    $collection[] = $section->policy()->collect($mode);
                }

                if (isset($field->group, $this->groups[$field->group])) {
                    /** @var Group $group */
                    $group = $this->groups[$field->group];
                    $collection[] = $group->policy()->collect($mode);
                }

        }

        return array_merge(...$collection);
    }


    public function resolvePermissions(AuthorizationInterface $resolver): void
    {
        foreach ($this->fields as $name => &$field) {
            /** @var Field $field */
            $resolved = $resolver->check($this->collectFieldPermissions($field), ['read', 'update']);
            $field->authorize($resolved);
        }
    }
}