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

trait HasNamings
{
    /** @var string|null Namespace */
    protected ?string $namespace;

    /** @var string|null Localization */
    protected ?string $localization;

    /** @var string|null Name of model for current template */
    protected ?string $model;

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