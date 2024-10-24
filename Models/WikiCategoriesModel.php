<?php

namespace CMW\Model\Wiki;

use CMW\Entity\Wiki\WikiCategoriesEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;

/**
 * Class @wikiCategorieModel
 * @package Wiki
 * @author Teyir
 * @version 1.0
 */
class WikiCategoriesModel extends AbstractModel
{
    public function createCategorie(string $name, string $description, ?string $icon, string $slug): ?WikiCategoriesEntity
    {
        $var = array(
            'name' => $name,
            'description' => $description,
            'icon' => $icon,
            'slug' => $slug
        );

        $sql = 'INSERT INTO cmw_wiki_categories (wiki_categories_name,wiki_categories_description,
                                 wiki_categories_slug,wiki_categories_icon) VALUES (:name, :description, :slug, :icon)';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getCategoryById($id);
        }

        return null;
    }

    public function getCategoryById($id): ?WikiCategoriesEntity
    {
        $sql = 'SELECT wiki_categories_id, wiki_categories_name, wiki_categories_description, wiki_categories_slug, 
                    wiki_categories_icon, wiki_categories_date_create, wiki_categories_date_update, 
                    wiki_categories_position, wiki_categories_is_define FROM cmw_wiki_categories WHERE wiki_categories_id =:id';

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array('id' => $id))) {
            return null;
        }

        $res = $res->fetch();

        return new WikiCategoriesEntity(
            $res['wiki_categories_id'],
            $res['wiki_categories_name'],
            $res['wiki_categories_description'],
            $res['wiki_categories_slug'],
            $res['wiki_categories_icon'],
            $res['wiki_categories_date_create'],
            $res['wiki_categories_date_update'],
            $res['wiki_categories_position'],
            $res['wiki_categories_is_define'],
            (new WikiArticlesModel())->getArticlesInCategory($res['wiki_categories_id'])
        );
    }

    public function getCategories(): array
    {
        $sql = 'SELECT * FROM cmw_wiki_categories';
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getCategoryById($wiki['wiki_categories_id']);
        }

        return $toReturn;
    }

    public function getUndefinedCategories(): array
    {
        $sql = 'SELECT * FROM cmw_wiki_categories WHERE wiki_categories_is_define = 0';
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getCategoryById($wiki['wiki_categories_id']);
        }

        return $toReturn;
    }

    public function getNumberOfUndefinedCategories(): int
    {
        $sql = 'SELECT * FROM cmw_wiki_categories WHERE wiki_categories_is_define = 0';
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $res = $req->execute();

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return 0;
    }

    public function getDefinedCategories(): array
    {
        $sql = 'SELECT * FROM cmw_wiki_categories WHERE wiki_categories_is_define = 1';
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getCategoryById($wiki['wiki_categories_id']);
        }

        return $toReturn;
    }

    public function updateCategorie(int $id, string $name, string $description, string $slug, ?string $icon, int $isDefine): ?WikiCategoriesEntity
    {
        $var = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'slug' => $slug,
            'icon' => $icon,
            'is_define' => $isDefine
        );

        $sql = 'UPDATE cmw_wiki_categories SET wiki_categories_name=:name, wiki_categories_description=:description, 
                               wiki_categories_slug=:slug, wiki_categories_icon=:icon, wiki_categories_date_update=now(), 
                               wiki_categories_is_define=:is_define WHERE wiki_categories_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            return $this->getCategoryById($id);
        }

        return null;
    }

    public function defineCategorie(int $id): void
    {
        $var = array(
            'id' => $id
        );

        $sql = 'UPDATE cmw_wiki_categories SET wiki_categories_is_define=1 WHERE wiki_categories_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function deleteCategorie(int $id): void
    {
        $sql = 'DELETE FROM cmw_wiki_categories WHERE wiki_categories_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array('id' => $id));
    }
}
