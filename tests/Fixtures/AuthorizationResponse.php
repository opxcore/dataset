<?php

/**
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\Tests\DataSet\Fixtures;

class AuthorizationResponse implements \OpxCore\Interfaces\Authorization\AuthorizationResponse
{
    protected array $abilities;

    /**
     * AuthorizationResponse constructor.
     *
     * @param array $abilities
     */
    public function __construct(array $abilities)
    {
        $this->abilities = $abilities;
    }

    /**
     * @inheritDoc
     */
    public function can(string $action): bool
    {
        return $this->abilities[$action] ?? false;
    }
}