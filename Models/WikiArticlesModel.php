<?php

namespace CMW\Model\Wiki;

use CMW\Controller\Core\CoreController;
use CMW\Entity\Wiki\WikiArticlesEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Editor\EditorManager;
use CMW\Manager\Package\AbstractModel;
use CMW\Manager\Uploads\ImagesManager;
use CMW\Model\Users\UsersModel;

/**
 * Class @wikiArticlesModel
 * @package Wiki
 * @author Teyir
 * @version 1.0
 */
class WikiArticlesModel extends AbstractModel
{
    public function createArticle(string $title, int $categoryId, ?string $icon, string $content, string $slug, int $authorId): ?WikiArticlesEntity
    {
        $var = array(
            'title' => $title,
            'category_id' => $categoryId,
            'icon' => $icon,
            'content' => $content,
            'author_id' => $authorId,
            'last_editor_id' => $authorId,
            'slug' => $slug
        );

        $sql = 'INSERT INTO cmw_wiki_articles (wiki_articles_title, wiki_articles_category_id, wiki_articles_icon, 
                               wiki_articles_content, wiki_articles_author_id, wiki_articles_last_editor_id, wiki_articles_slug) 
                        VALUES (:title, :category_id, :icon, :content, :author_id,:last_editor_id, :slug)';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getArticleById($id);
        }

        return null;
    }

    public function updateArticle(int $id, string $title, int $categoryId, string $content, ?string $icon, int $lastEditor, int $isDefine): ?WikiArticlesEntity
    {
        $var = array(
            'id' => $id,
            'title' => $title,
            'category_id' => $categoryId,
            'content' => $content,
            'icon' => $icon,
            'last_editor' => $lastEditor,
            'is_define' => $isDefine
        );

        $sql = 'UPDATE cmw_wiki_articles SET wiki_articles_title=:title,wiki_articles_category_id=:category_id, wiki_articles_content=:content, 
                             wiki_articles_icon=:icon, wiki_articles_date_update=now(), 
                             wiki_articles_last_editor_id=:last_editor, wiki_articles_is_define=:is_define WHERE wiki_articles_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            return $this->getArticleById($id);
        }

        return null;
    }

    public function getArticleById(?int $id): ?WikiArticlesEntity
    {
        $sql = 'SELECT wiki_articles_id, wiki_articles_category_id, wiki_articles_position, 
       wiki_articles_is_define, wiki_articles_title, wiki_articles_content, wiki_articles_slug, 
       wiki_articles_icon, wiki_articles_date_create, wiki_articles_date_update, wiki_articles_author_id,
       wiki_articles_last_editor_id FROM cmw_wiki_articles WHERE wiki_articles_id =:id ORDER BY wiki_articles_position ASC';

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array('id' => $id))) {
            return null;
        }

        $res = $res->fetch();

        if (!$res) {
            return null;
        }

        $author = (new UsersModel())->getUserById($res['wiki_articles_author_id']);
        $lastEditor = (new UsersModel())->getUserById($res['wiki_articles_last_editor_id']);

        return new WikiArticlesEntity(
            $res['wiki_articles_id'],
            $res['wiki_articles_category_id'],
            $res['wiki_articles_position'],
            $res['wiki_articles_is_define'],
            $res['wiki_articles_title'],
            $res['wiki_articles_content'],
            $res['wiki_articles_content'],
            $res['wiki_articles_slug'],
            $res['wiki_articles_icon'],
            $res['wiki_articles_date_create'],
            $res['wiki_articles_date_update'],
            $author,
            $lastEditor,
        );
    }

    public function getArticles(): array
    {
        $sql = 'SELECT * FROM cmw_wiki_articles ORDER BY wiki_articles_position ASC';
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getArticleById($wiki['wiki_articles_id']);
        }

        return $toReturn;
    }

    public function getUndefinedArticles(): array
    {
        $sql = 'SELECT * FROM cmw_wiki_articles WHERE wiki_articles_is_define = 0 ORDER BY wiki_articles_position ASC';
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getArticleById($wiki['wiki_articles_id']);
        }

        return $toReturn;
    }

    public function getNumberOfUndefinedArticles(): int
    {
        $sql = 'SELECT * FROM cmw_wiki_articles WHERE wiki_articles_is_define = 0 ORDER BY wiki_articles_position ASC';
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $res = $req->execute();

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return 0;
    }

    public function getArticlesInCategory(int $id): array
    {
        $sql = 'SELECT * FROM cmw_wiki_articles WHERE wiki_articles_category_id =:categoryId AND wiki_articles_is_define = 1 ORDER BY wiki_articles_position ASC';

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array('categoryId' => $id))) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getArticleById($wiki['wiki_articles_id']);
        }

        return $toReturn;
    }

    public function defineArticle(int $id): void
    {
        $sql = 'UPDATE cmw_wiki_articles SET wiki_articles_is_define=1 WHERE wiki_articles_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array('id' => $id));
    }

    public function deleteArticle(int $id): void
    {
        $articleContent = $this->getArticleById($id)->getContent();
        EditorManager::getInstance()->deleteEditorImageInContent($articleContent);

        $sql = 'DELETE FROM cmw_wiki_articles WHERE wiki_articles_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array('id' => $id));
    }

    public function downPositionArticle(int $id, int $position): void
    {
        $sql = 'UPDATE cmw_wiki_articles SET wiki_articles_position=:position WHERE wiki_articles_id=:id';

        $newPosition = $position - 1;
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array('id' => $id, 'position' => $newPosition));
    }

    public function upPositionArticle(int $id, int $position): void
    {
        $sql = 'UPDATE cmw_wiki_articles SET wiki_articles_position=:position WHERE wiki_articles_id=:id';

        $newPosition = $position + 1;
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array('id' => $id, 'position' => $newPosition));
    }

    public function getArticleBySlug(string $slug): ?WikiArticlesEntity
    {
        $sql = 'SELECT wiki_articles_id, wiki_articles_category_id, wiki_articles_position, 
       wiki_articles_is_define, wiki_articles_title, wiki_articles_content, wiki_articles_slug, 
       wiki_articles_icon,  wiki_articles_date_create, wiki_articles_date_update, wiki_articles_author_id, 
       wiki_articles_last_editor_id FROM cmw_wiki_articles WHERE wiki_articles_slug =:slug ORDER BY wiki_articles_position ASC';

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array('slug' => $slug))) {
            return null;
        }

        $res = $res->fetch();

        $author = (new UsersModel())->getUserById($res['wiki_articles_author_id']);
        $lastEditor = (new UsersModel())->getUserById($res['wiki_articles_last_editor_id']);

        return new WikiArticlesEntity(
            $res['wiki_articles_id'],
            $res['wiki_articles_category_id'],
            $res['wiki_articles_position'],
            $res['wiki_articles_is_define'],
            $res['wiki_articles_title'],
            $res['wiki_articles_content'],
            $res['wiki_articles_content'],
            $res['wiki_articles_slug'],
            $res['wiki_articles_icon'],
            $res['wiki_articles_date_create'],
            $res['wiki_articles_date_update'],
            $author,
            $lastEditor,
        );
    }

    public function getFirstArticle(): ?WikiArticlesEntity
    {
        $sql = 'SELECT wiki_articles_id FROM `cmw_wiki_articles` 
                        ORDER BY `cmw_wiki_articles`.`wiki_articles_category_id` ASC LIMIT 1';

        $db = DatabaseManager::getInstance();
        $res = $db->query($sql);

        $res = $res->fetch();

        if (!$res) {
            return null;
        }

        return $this->getArticleById($res['wiki_articles_id']);
    }
}
