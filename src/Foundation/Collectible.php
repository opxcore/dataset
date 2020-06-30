<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Foundation;

use OpxCore\DataSet\Exceptions\InvalidDefinitionException;
use OpxCore\DataSet\Traits\HasNamings;

/**
 * Base class for section, group and field.
 *
 * @package OpxCore\DataSet
 */
class Collectible
{
    use HasNamings;

    /** @var string Name of collectible */
    protected string $name;

    /** @var string|null Translation key for collectible */
    protected ?string $label;

    /** @var string|null Direct caption of collectible */
    protected ?string $caption;

    /** @var Policy Policy to authorize */
    protected Policy $policy;

    /** @var string|null Whether collectible belongs to fields, groups or sections */
    protected ?string $context;

    /**
     * Collectible constructor.
     *
     * @param array $properties Array of base properties definition. `name` is required.
     * @param string|null $context
     * @param string|null $namespace
     * @param string|null $localization
     * @param string|null $model
     *
     * @return  void
     */
    public function __construct(array $properties, ?string $namespace = null, ?string $localization = null, ?string $model = null, string $context = null)
    {
        if (empty($properties['name'])) {
            throw new InvalidDefinitionException('Field name property must be defined');
        }

        $this->name = $properties['name'];
        $this->setLabel($properties['label'] ?? null);
        $this->caption = $properties['caption'] ?? null;
        $this->policy = new Policy($properties['policy'] ?? null);
        $this->setNamespace($namespace);
        $this->setLocalization($localization);
        $this->setModel($model);
        $this->setContext($context);
    }

    /**
     * Get name of collectible.
     *
     * @return  string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Set new label for collectible.
     *
     * @param string|null $label
     *
     * @return  void
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * Return or generate label for collectible.
     *
     * @return  string|null
     */
    public function label(): ?string
    {
        if ($this->label !== null) {
            return $this->label;
        }

        $label = $this->namespace ? "{$this->namespace}::" : null;
        $label .= $this->localization ? "{$this->localization}." : null;
        $label .= $this->model ? "{$this->model}." : null;
        $label .= $this->context ? "{$this->context}." : null;
        $label .= $this->name;

        return $label;
    }

    /**
     * Set new caption for collectible.
     *
     * @param string|null $caption
     *
     * @return  void
     */
    public function setCaption(?string $caption): void
    {
        $this->caption = $caption;
    }

    /**
     * Get caption for collectible. If caption
     *
     * @return  string|null
     */
    public function caption(): ?string
    {
        // TODO: if caption not set make translation of label
        return $this->caption;
    }

    /**
     * Set context.
     *
     * @param string|null $context
     *
     * @return  void
     */
    public function setContext(?string $context): void
    {
        $this->context = $context;
    }

    /**
     * Get context.
     *
     * @return  string|null
     */
    public function context(): ?string
    {
        return $this->context;
    }

    /**
     * Extend another collectible by current:
     * - Add permissions to inherit.
     *
     * @param Collectible $collectible
     *
     * @return  void
     */
    public function extend(Collectible $collectible): void
    {
        $this->setInheritedPolicy($collectible->policy());
    }

    /**
     * Get permissions.
     *
     * @return  Policy
     */
    public function policy(): ?Policy
    {
        return $this->policy;
    }

    /**
     * Set inherited permissions.
     *
     * @param Policy $permissions
     *
     * @return  void
     */
    public function setInheritedPolicy(Policy $permissions): void
    {
        $this->policy->setInherited($permissions);
    }
}