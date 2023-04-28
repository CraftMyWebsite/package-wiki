<?php

namespace CMW\Implementation\Wiki;

use CMW\Interface\Core\IMenus;

class WikiMenusImplementations implements IMenus {

    public function getRoutes(): array
    {
        return [
            'wiki'
        ];
    }

    public function getPackageName(): string
    {
        return 'Wiki';
    }
}