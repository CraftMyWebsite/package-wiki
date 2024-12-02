<?php

namespace CMW\Package\Wiki;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return 'Wiki';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function authors(): array
    {
        return ['Teyir'];
    }

    public function isGame(): bool
    {
        return false;
    }

    public function isCore(): bool
    {
        return false;
    }

    public function menus(): ?array
    {
        return [
            new PackageMenuType(
                icon: 'fas fa-book',
                title: 'Wiki',
                url: 'wiki',
                permission: 'wiki.show'
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ['Core'];
    }

    public function uninstall(): bool
    {
        // Return true, we don't need other operations for uninstall.
        return true;
    }
}
