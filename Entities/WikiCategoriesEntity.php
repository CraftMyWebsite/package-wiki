<?php

namespace CMW\Entity\Wiki;

use CMW\Manager\Package\AbstractEntity;
use CMW\Utils\Date;

class WikiCategoriesEntity extends AbstractEntity
{
    private int $id;
    private string $name;
    private string $description;
    private string $slug;
    private ?string $icon;
    private string $dateCreate;
    private ?string $dateUpdate;
    private int $position;
    private int $isDefine;
    /** @var WikiArticlesEntity|WikiArticlesEntity[] $articles */
    private ?array $articles;

    /**
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $slug
     * @param string|null $icon
     * @param string $dateCreate
     * @param string|null $dateUpdate
     * @param int $position
     * @param int $isDefine
     * @param WikiArticlesEntity[]|null $articles
     */
    public function __construct(int $id, string $name, string $description, string $slug, ?string $icon, string $dateCreate, ?string $dateUpdate, int $position, int $isDefine, ?array $articles)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->slug = $slug;
        $this->icon = $icon;
        $this->dateCreate = $dateCreate;
        $this->dateUpdate = $dateUpdate;
        $this->position = $position;
        $this->isDefine = $isDefine;
        $this->articles = $articles;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getDateCreate(): string
    {
        return Date::formatDate($this->dateCreate);
    }

    /**
     * @return string|null
     */
    public function getDateUpdate(): ?string
    {
        return Date::formatDate($this->dateUpdate);
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getIsDefine(): int
    {
        return $this->isDefine;
    }

    /**
     * @return WikiArticlesEntity[]|null
     */
    public function getArticles(): ?array
    {
        return $this->articles;
    }
}
