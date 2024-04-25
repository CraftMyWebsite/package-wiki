<?php

namespace CMW\Implementation\Wiki\Core;

use CMW\Interface\Core\IMenus;
use CMW\Manager\Lang\LangManager;

class WikiMenusImplementations implements IMenus {

    public function getRoutes(): array
    {
        return [
            LangManager::translate('wiki.wiki') => 'wiki'
        ];
    }

    public function getPackageName(): string
    {
        return 'Wiki';
    }
}