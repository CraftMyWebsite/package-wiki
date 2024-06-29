<?php

namespace CMW\Permissions\Wiki;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Permission\IPermissionInit;
use CMW\Manager\Permission\PermissionInitType;

class Permissions implements IPermissionInit
{
    public function permissions(): array
    {
        return [
            new PermissionInitType(
                code: 'wiki.show',
                description: LangManager::translate('wiki.permissions.wiki.show'),
            ),
            new PermissionInitType(
                code: 'wiki.category.define',
                description: LangManager::translate('wiki.permissions.wiki.category.define'),
            ),
            new PermissionInitType(
                code: 'wiki.category.add',
                description: LangManager::translate('wiki.permissions.wiki.category.add'),
            ),
            new PermissionInitType(
                code: 'wiki.category.edit',
                description: LangManager::translate('wiki.permissions.wiki.category.edit'),
            ),
            new PermissionInitType(
                code: 'wiki.category.delete',
                description: LangManager::translate('wiki.permissions.wiki.category.delete'),
            ),
            new PermissionInitType(
                code: 'wiki.article.define',
                description: LangManager::translate('wiki.permissions.wiki.article.define'),
            ),
            new PermissionInitType(
                code: 'wiki.article.add',
                description: LangManager::translate('wiki.permissions.wiki.article.add'),
            ),
            new PermissionInitType(
                code: 'wiki.article.edit',
                description: LangManager::translate('wiki.permissions.wiki.article.edit'),
            ),
            new PermissionInitType(
                code: 'wiki.article.delete',
                description: LangManager::translate('wiki.permissions.wiki.article.delete'),
            ),
        ];
    }

}