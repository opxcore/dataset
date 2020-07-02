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

use OpxCore\Interfaces\Authorization\AuthorizationInterface;

class Authorization implements AuthorizationInterface
{
    /**
     * @inheritDoc
     */
    public function check(?array $permissions, array $actions = ['create', 'read', 'update', 'delete']): AuthorizationResponse
    {
        $abilities = [
            'read' => true,
            'update' => true,
        ];

        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                if ($permission && $permission['read'] === false) {
                    $abilities['read'] = false;
                }
                if ($permission && $permission['update'] === false) {
                    $abilities['update'] = false;
                }
            }
        }

        return new AuthorizationResponse($abilities);
    }
}