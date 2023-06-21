<?php

namespace CMW\Model\Wiki;

use CMW\Entity\Wiki\WikiArticlesEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use CMW\Model\Users\UsersModel;

/**
 * Class @wikiArticlesModel
 * @package Wiki
 * @author Teyir
 * @version 1.0
 */
class WikiArticlesModel extends AbstractModel
{

    public function createArticle(string $title, int $categoryId, string $icon, string $content, string $slug, int $authorId): ?WikiArticlesEntity
    {

        $var = array(
            "title" => $title,
            "category_id" => $categoryId,
            "icon" => $icon,
            "content" => $content,
            "author_id" => $authorId,
            "last_editor_id" => $authorId,
            "slug" => $slug
        );

        $sql = "INSERT INTO cmw_wiki_articles (wiki_articles_title, wiki_articles_category_id, wiki_articles_icon, 
                               wiki_articles_content, wiki_articles_author_id, wiki_articles_last_editor_id, wiki_articles_slug) 
                        VALUES (:title, :category_id, :icon, :content, :author_id,:last_editor_id, :slug)";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getArticleById($id);
        }

        return null;
    }

    public function updateArticle(int $id, string $title, int $categoryId, string $content, string $icon, int $lastEditor, int $isDefine): ?WikiArticlesEntity
    {
        $var = array(
            "id" => $id,
            "title" => $title,
            "category_id" => $categoryId,
            "content" => $content,
            "icon" => $icon,
            "last_editor" => $lastEditor,
            "is_define" => $isDefine
        );

        $sql = "UPDATE cmw_wiki_articles SET wiki_articles_title=:title,wiki_articles_category_id=:category_id, wiki_articles_content=:content, 
                             wiki_articles_icon=:icon, wiki_articles_date_update=now(), 
                             wiki_articles_last_editor_id=:last_editor, wiki_articles_is_define=:is_define WHERE wiki_articles_id=:id";


        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            return $this->getArticleById($id);
        }

        return null;
    }

    private function translatePage($content): string
    {
        $content = json_decode($content, false);

        $blocks = $content->blocks;
        $convertedHtml = "";
        foreach ($blocks as $block) {
            switch ($block->type) {
                case "header":
                    $level = $block->data->level;
                    $text = $block->data->text;
                    $convertedHtml .= "<h$level class='editor_h$level'>$text</h$level>";
                    break;

                case "embed":
                    $src = $block->data->embed;
                    $convertedHtml .=
                        <<<HTML
                            <div>
                                <iframe width="560" height="315" src="$src" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                            </div>
                        HTML;
                    break;

                case "paragraph":
                    $text = $block->data->text;
                    $convertedHtml .=
                        <<<HTML
                            <p class='editor_p'>$text</p>
                        HTML;
                    break;

                case "delimiter":
                    $convertedHtml .=
                        <<<HTML
                            <hr class='editor_hr'>
                        HTML;
                    break;

                case "image":
                    $src = $block->data->file->url;
                    $caption = $block->data->caption;
                    $convertedHtml .=
                        <<<HTML
                            <img class="editor_img" src="$src" title="$caption" alt="$caption" /><br /><em>$caption</em>
                        HTML;
                    break;

                case "list":
                    $convertedHtml .= ($block->data->style === "unordered") ? "<ul class='editor_ul' style='list-style-type: disc'>" : "<ol class='editor_ol' style='list-style-type: decimal'>";
                    foreach ($block->data->items as $item) {
                        $convertedHtml .=
                            <<<HTML
                                <li class='editor_li'>$item</li>
                            HTML;
                    }
                    $convertedHtml .= ($block->data->style === "unordered") ? "</ul>" : "</ol>";
                    break;

                case "quote":
                    $text = $block->data->text;
                    $caption = $block->data->caption;
                    $convertedHtml .=
                        <<<HTML
                            <figure class='editor_figure'>
                                <blockquote class='editor_blockquote'>
                                    <p class='editor_p'>$text</p> 
                                </blockquote>
                                <figcaption class='editor_figcaption'>$caption</figcaption>
                            </figure>
                        HTML;
                    break;



                case "code":
                    $text = $block->data->code;
                    $textconverted = htmlspecialchars($text, ENT_COMPAT);
                    $convertedHtml .=
                        <<<HTML
                        <div class="editor_allcode">
                            <pre class="editor_pre">
                                <code class="editor_code">$textconverted</code>
                            </pre>
                        </div>
                        HTML;
                    break;

                case "warning":
                    $title = $block->data->title;
                    $message = $block->data->message;
                    $convertedHtml .=
                        <<<HTML
                            <div class="editor_warning">
                                <div class="editor_warning-title">
                                    <p class='editor_p'>$title</p>
                                </div>
                                <div class="editor_warning-content">
                                    <p class='editor_p'>$message</p>
                                </div>
                            </div>
                        HTML;
                    break;

                case "linkTool":
                    $link = $block->data->link;
                    $convertedHtml .=
                        <<<HTML
                            <a class='editor_a' href="$link">$link</a>
                        HTML;
                    break;

                case "table":
                    $convertedHtml .= "<table class='editor_table'><tbody class='editor_tbody'>";
                    foreach ($block->data->content as $tr) {
                        $convertedHtml .= "<tr class='editor_tr'>";
                        foreach ($tr as $td) {
                            $convertedHtml .= "<td class='editor_td'>$td</td>";
                        }
                        $convertedHtml .= "</tr>";

                    }
                    $convertedHtml .= "</table></tbody>";
                    break;
            }
        }

        return $convertedHtml;
    }

    public function getArticleById(?int $id): ?WikiArticlesEntity
    {

        $sql = "SELECT wiki_articles_id, wiki_articles_category_id, wiki_articles_position, 
       wiki_articles_is_define, wiki_articles_title, wiki_articles_content, wiki_articles_slug, 
       wiki_articles_icon, wiki_articles_date_create, wiki_articles_date_update, wiki_articles_author_id,
       wiki_articles_last_editor_id FROM cmw_wiki_articles WHERE wiki_articles_id =:id ORDER BY wiki_articles_position ASC";

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array("id" => $id))) {
            return null;
        }

        $res = $res->fetch();
        
        if (!$res){
            return null;
        }

        $author = (new UsersModel())->getUserById($res["wiki_articles_author_id"]);
        $lastEditor = (new UsersModel())->getUserById($res["wiki_articles_last_editor_id"]);

        return new WikiArticlesEntity(
            $res['wiki_articles_id'],
            $res['wiki_articles_category_id'],
            $res['wiki_articles_position'],
            $res['wiki_articles_is_define'],
            $res['wiki_articles_title'],
            $this->translatePage($res["wiki_articles_content"]),
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
        $sql = "SELECT * FROM cmw_wiki_articles ORDER BY wiki_articles_position ASC";
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getArticleById($wiki["wiki_articles_id"]);
        }

        return $toReturn;
    }

    public function getUndefinedArticles(): array
    {
        $sql = "SELECT * FROM cmw_wiki_articles WHERE wiki_articles_is_define = 0 ORDER BY wiki_articles_position ASC";
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getArticleById($wiki["wiki_articles_id"]);
        }

        return $toReturn;
    }

    public function getNumberOfUndefinedArticles(): int
    {
        $sql = "SELECT * FROM cmw_wiki_articles WHERE wiki_articles_is_define = 0 ORDER BY wiki_articles_position ASC";
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
        $sql = "SELECT * FROM cmw_wiki_articles WHERE wiki_articles_category_id =:categoryId AND wiki_articles_is_define = 1 ORDER BY wiki_articles_position ASC";

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array("categoryId" => $id))) {
            return array();
        }

        $toReturn = array();

        while ($wiki = $res->fetch()) {
            $toReturn[] = $this->getArticleById($wiki["wiki_articles_id"]);
        }

        return $toReturn;
    }

    public function defineArticle(int $id): void
    {
        $sql = "UPDATE cmw_wiki_articles SET wiki_articles_is_define=1 WHERE wiki_articles_id=:id";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array("id" => $id));
    }

    public function deleteArticle(int $id): void
    {
        $sql = "DELETE FROM cmw_wiki_articles WHERE wiki_articles_id=:id";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array("id" => $id));
    }

    public function downPositionArticle(int $id, int $position): void
    {

        $sql = "UPDATE cmw_wiki_articles SET wiki_articles_position=:position WHERE wiki_articles_id=:id";
        
        $newPosition = $position - 1;
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array("id" => $id, "position" => $newPosition));
    }

    public function upPositionArticle(int $id, int $position): void
    {

        $sql = "UPDATE cmw_wiki_articles SET wiki_articles_position=:position WHERE wiki_articles_id=:id";
        
        $newPosition = $position + 1;
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array("id" => $id, "position" => $newPosition));
    }

    public function getArticleBySlug(string $slug): ?WikiArticlesEntity
    {

        $sql = "SELECT wiki_articles_id, wiki_articles_category_id, wiki_articles_position, 
       wiki_articles_is_define, wiki_articles_title, wiki_articles_content, wiki_articles_slug, 
       wiki_articles_icon,  wiki_articles_date_create, wiki_articles_date_update, wiki_articles_author_id, 
       wiki_articles_last_editor_id FROM cmw_wiki_articles WHERE wiki_articles_slug =:slug ORDER BY wiki_articles_position ASC";

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array("slug" => $slug))) {
            return null;
        }

        $res = $res->fetch();

        $author = (new UsersModel())->getUserById($res["wiki_articles_author_id"]);
        $lastEditor = (new UsersModel())->getUserById($res["wiki_articles_last_editor_id"]);

        return new WikiArticlesEntity(
            $res['wiki_articles_id'],
            $res['wiki_articles_category_id'],
            $res['wiki_articles_position'],
            $res['wiki_articles_is_define'],
            $res['wiki_articles_title'],
            $this->translatePage($res["wiki_articles_content"]),
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
        $sql = "SELECT wiki_articles_id FROM `cmw_wiki_articles` 
                        ORDER BY `cmw_wiki_articles`.`wiki_articles_category_id` ASC LIMIT 1";

        $db = DatabaseManager::getInstance();
        $res = $db->query($sql);

        $res = $res->fetch();
        
        if(!$res){
            return null;
        }

        return $this->getArticleById($res['wiki_articles_id']);
    }


}