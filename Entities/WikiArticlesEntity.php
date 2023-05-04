<?php

namespace CMW\Entity\Wiki;

use CMW\Entity\Users\UserEntity;

class WikiArticlesEntity
{
    private int $id;
    private int $categoryId;
    private int $position;
    private int $isDefine;
    private string $title;
    private string $content;
    private string $contentNt;
    private string $slug;
    private string $icon;
    private string $dateCreate;
    private string $dateUpdate;
    private UserEntity $author;
    private UserEntity $lastEditor;

    /**
     * @param int $id
     * @param int $categoryId
     * @param int $position
     * @param int $isDefine
     * @param string $title
     * @param string $content
     * @param string $contentNt
     * @param string $slug
     * @param string $icon
     * @param string $dateCreate
     * @param string $dateUpdate
     * @param \CMW\Entity\Users\UserEntity $author
     * @param \CMW\Entity\Users\UserEntity $lastEditor
     */
    public function __construct(int $id, int $categoryId, int $position, int $isDefine, string $title, string $content, string $contentNt, string $slug, string $icon, string $dateCreate, string $dateUpdate, UserEntity $author, UserEntity $lastEditor)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->position = $position;
        $this->isDefine = $isDefine;
        $this->title = $title;
        $this->content = $content;
        $this->contentNt = $contentNt;
        $this->slug = $slug;
        $this->icon = $icon;
        $this->dateCreate = $dateCreate;
        $this->dateUpdate = $dateUpdate;
        $this->author = $author;
        $this->lastEditor = $lastEditor;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContentNotTranslate(): string
    {
        return $this->contentNt;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getDateCreate(): string
    {
        return $this->dateCreate;
    }

    /**
     * @return string
     */
    public function getDateUpdate(): string
    {
        return $this->dateUpdate;
    }

    /**
     * @return \CMW\Entity\Users\UserEntity
     */
    public function getAuthor(): UserEntity
    {
        return $this->author;
    }

    /**
     * @return \CMW\Entity\Users\UserEntity
     */
    public function getLastEditor(): UserEntity
    {
        return $this->lastEditor;
    }

}