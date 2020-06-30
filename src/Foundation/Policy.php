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

class Policy
{
    /** @var mixed Permissions set */
    protected $permissions;

    /** @var string Mode of inheritance */
    protected string $mode = 'inherit';

    /** @var Policy|null Inherited permissions set */
    protected ?Policy $inherited;

    /**
     * Permissions constructor.
     *
     * @param array|null $policy
     *
     * @return  void
     */
    public function __construct(?array $policy = null)
    {
        $this->permissions = $policy['permissions'] ?? null;
        $this->mode = $policy['mode'] ?? 'inherit';
    }

    /**
     * Set permissions.
     *
     * @param mixed $permissions
     *
     * @return  void
     */
    public function setPermissions($permissions): void
    {
        $this->permissions = $permissions;
    }

    /**
     * Get permissions.
     *
     * @return  mixed
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * Set inheritance mode.
     *
     * @param mixed $mode
     *
     * @return  void
     */
    public function setMode(string $mode = 'inherit'): void
    {
        $this->mode = $mode;
    }

    /**
     * Get inheritance mode.
     *
     * @return  mixed
     */
    public function mode()
    {
        return $this->mode;
    }

    /**
     * Set inherited permissions.
     *
     * @param Policy|mixed $policy
     *
     * @return  void
     */
    public function setInherited($policy): void
    {
        if ($policy instanceof self) {
            $this->inherited = $policy;
        } else {
            $this->inherited = new Policy($policy);
        }
    }

    /**
     * Get inherited permissions.
     *
     * @return  Policy
     */
    public function inherited(): Policy
    {
        return $this->inherited;
    }
}