<?php

namespace CMW\Package\Wiki;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return "Wiki";
    }

    public function version(): string
    {
        return "0.0.1";
    }

    public function authors(): array
    {
        return ["Teyir"];
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
                lang: "fr",
                icon: "fas fa-book",
                title: "Wiki",
                url: "wiki/list",
                permission: "wiki.show",
                subMenus: []
            ),
            new PackageMenuType(
                lang: "en",
                icon: "fas fa-book",
                title: "Wiki",
                url: "wiki/list",
                permission: "wiki.show",
                subMenus: []
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ["Core"];
    }
}