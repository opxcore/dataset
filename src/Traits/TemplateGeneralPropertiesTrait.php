<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\DataSet\Traits;

trait TemplateGeneralPropertiesTrait
{
    /** @var string|null Template to extend */
    protected ?string $extends;

    /** @var bool Whether cache disabled for this template */
    protected bool $caching = false;

    /** @var bool Whether extending disabled for this template */
    protected bool $extending = false;

    /** @var string|null Namespace */
    protected ?string $namespace;

    /** @var string|null Localization */
    protected ?string $localization;

    /** @var string|null Name of model for current template */
    protected ?string $model;


    /**
     * Get template to extend by current.
     *
     * @return  string|null
     */
    public function extends(): ?string
    {
        return $this->extends;
    }

    /**
     * Set template to extend by current.
     *
     * @param string|null $extends
     *
     * @return  void
     */
    public function setExtends(?string $extends): void
    {
        if ($extends === null || ($position = strpos($extends, '::')) > 0) {
            $this->extends = $extends;
        } else {
            $this->extends = '*' . ($position === 0 ? null : '::') . $extends;
        }
    }

    /**
     * Enable template caching.
     *
     * @return  void
     */
    public function enableCache(): void
    {
        $this->caching = true;
    }

    /**
     * Disable template caching.
     *
     * @return  void
     */
    public function disableCache(): void
    {
        $this->caching = false;
    }

    /**
     * Whether cache enabled.
     *
     * @return  bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->caching;
    }

    /**
     * Enable template extending.
     *
     * @return  void
     */
    public function enableExtending(): void
    {
        $this->extending = true;
    }

    /**
     * Disable template extending.
     *
     * @return  void
     */
    public function disableExtending(): void
    {
        $this->extending = false;
    }

    /**
     * Whether extending enabled.
     *
     * @return  bool
     */
    public function isExtendingEnabled(): bool
    {
        return $this->extending;
    }

    /**
     * Set template flags.
     *
     * @param array $flags
     *
     * @return  void
     */
    public function setFlags(array $flags): void
    {
        if (in_array('disable cache', $flags, true)) {
            $this->disableCache();
        } else {
            $this->enableCache();
        }

        if (in_array('disable extending', $flags, true)) {
            $this->disableExtending();
        } else {
            $this->enableExtending();
        }
    }

    /**
     * Set namespace.
     *
     * @param string|null $namespace
     *
     * @return  void
     */
    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * Get namespace.
     *
     * @return string|null
     */
    public function namespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * Set localization.
     *
     * @param string|null $localization
     */
    public function setLocalization(?string $localization): void
    {
        $this->localization = $localization;
    }

    /**
     * Get localization.
     *
     * @return string|null
     */
    public function localization(): ?string
    {
        return $this->localization;
    }

    /**
     * Set model name.
     *
     * @param string|null $model
     */
    public function setModel(?string $model): void
    {
        $this->model = $model;
    }

    /**
     * Get model name.
     *
     * @return string|null
     */
    public function model(): ?string
    {
        return $this->model;
    }
}