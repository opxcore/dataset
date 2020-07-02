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
    /** 'unset' disables all inherited and current permissions. */
    public const MODE_UNSET = 0;

    /** 'no inherit' means only current permissions would be checked. */
    public const MODE_NO_INHERIT = 1;

    /** 'inherit current' will inherit all permissions except parent template permissions. */
    public const MODE_INHERIT_CURRENT = 2;

    /** 'inherit all' (by default) will inherit all permissions regular way. */
    public const MODE_INHERIT_ALL = 4;

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
        $this->setPermissions($policy['permissions'] ?? null);
        $this->setMode($policy['mode'] ?? null);
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
     * @param int|string|null $mode
     *
     * @return  void
     */
    public function setMode($mode): void
    {
        if ($mode === null) {
            $this->mode = self::MODE_INHERIT_ALL;
        } else if (is_int($mode)) {
            $this->mode = $mode;
        } else {
            switch ($mode) {
                case 'unset':
                    $this->mode = self::MODE_UNSET;
                    break;
                case 'no inherit':
                    $this->mode = self::MODE_NO_INHERIT;
                    break;
                case 'inherit current':
                    $this->mode = self::MODE_INHERIT_CURRENT;
                    break;
                case 'inherit all':
                default:
                    $this->mode = self::MODE_INHERIT_ALL;
            }
        }
    }

    /**
     * Get inheritance mode.
     *
     * @return  int
     */
    public function mode(): int
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

    /**
     * Collect actual permissions.
     *
     * @param int|null $mode Mode to override
     *
     * @return  array
     */
    public function collect(?int $mode = null): array
    {
        switch ($mode ?? $this->mode()) {
            case self::MODE_UNSET:
                // Unset all permissions
                return [];
                break;
            case self::MODE_NO_INHERIT:
            case self::MODE_INHERIT_CURRENT:
                // Get only current or current template permission inheriting
                return [$this->permissions()];
                break;
            case self::MODE_INHERIT_ALL:
            default:
                // Get all permissions chain
                return array_merge([$this->permissions()], isset($this->inherited) ? $this->inherited->collect() : []);
                break;
        }
    }
}