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
use OpxCore\DataSet\Traits\HasNamings;
use OpxCore\DataSet\Traits\TemplateGeneralPropertiesTrait;
use OpxCore\DataSet\Foundation\Collection;

class Template
{
    use TemplateGeneralPropertiesTrait,
        HasNamings;

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
     * Extend another template by current.
     *
     * @param Template $template
     *
     * @return  void
     */
    public function extend(Template $template): void
    {
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
}