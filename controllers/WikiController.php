<?php

namespace CMW\Controller\Wiki;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Core\EditorController;
use CMW\Controller\users\UsersController;
use CMW\Model\users\UsersModel;
use CMW\Model\wiki\WikiArticlesModel;
use CMW\Model\wiki\WikiCategoriesModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\Response;
use CMW\Utils\View;
use JetBrains\PhpStorm\NoReturn;


/**
 * Class: @wikiController
 * @package wiki
 * @author Teyir
 * @version 1.0
 */
class WikiController extends CoreController
{

    public static string $themePath;
    private WikiArticlesModel $wikiArticlesModel;
    private WikiCategoriesModel $wikiCategoriesModel;

    public function __construct($themePath = null)
    {
        parent::__construct($themePath);
        $this->wikiArticlesModel = new WikiArticlesModel();
        $this->wikiCategoriesModel = new WikiCategoriesModel();
    }

    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/wiki")]
    #[Link("/list", Link::GET, [], "/cmw-admin/wiki")]
    public function frontWikiListAdmin(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.show");


        //Get all undefined articles
        $undefinedArticles = $this->wikiArticlesModel->getUndefinedArticles();

        $undefinedCategories = $this->wikiCategoriesModel->getUndefinedCategories();

        $categories = $this->wikiCategoriesModel->getDefinedCategories();
        $currentCategories = $this->wikiCategoriesModel->getCategories();


        //Include the view file ("views/list.admin.view.php").

        View::createAdminView('wiki', 'list')
            ->addVariableList(["currentCategories" => $currentCategories, "categories" => $categories, "undefinedArticles" => $undefinedArticles, "undefinedCategories" => $undefinedCategories])
            ->view();
    }

    #[Link("/list", Link::GET, [], "/cmw-admin/wiki")]
    public function addCategorie(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.add");


        View::createAdminView('wiki', 'addCategorie')
            ->view();
    }


    #[Link("/list", Link::POST, [], "/cmw-admin/wiki")]
    public function addCategoriePost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.add");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");

        $slug = Utils::normalizeForSlug(filter_input(INPUT_POST, "slug"));


        $this->wikiCategoriesModel->createCategorie($name, $description, $icon, $slug);
        header("location: ../wiki/list");
    }

    #[Link("/article/add/:cat", Link::GET, ["cat" => "[0-9]+"], "/cmw-admin/wiki")]
    public function addArticle(int $cat): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.add");

        //Get all undefined articles
        $undefinedArticles = $this->wikiArticlesModel->getUndefinedArticles();

        $undefinedCategories = $this->wikiCategoriesModel->getUndefinedCategories();

        $categories = $this->wikiCategoriesModel->getDefinedCategories();
        $currentCategories = $this->wikiCategoriesModel->getCategories();

        View::createAdminView('wiki', 'addArticle')
            ->addScriptBefore("admin/resources/vendors/editorjs/plugins/header.js",
                    "admin/resources/vendors/editorjs/plugins/image.js",
                    "admin/resources/vendors/editorjs/plugins/delimiter.js",
                    "admin/resources/vendors/editorjs/plugins/list.js",
                    "admin/resources/vendors/editorjs/plugins/quote.js",
                    "admin/resources/vendors/editorjs/plugins/code.js",
                    "admin/resources/vendors/editorjs/plugins/table.js",
                    "admin/resources/vendors/editorjs/plugins/link.js",
                    "admin/resources/vendors/editorjs/plugins/warning.js",
                    "admin/resources/vendors/editorjs/plugins/embed.js",
                    "admin/resources/vendors/editorjs/plugins/marker.js",
                    "admin/resources/vendors/editorjs/plugins/underline.js",
                    "admin/resources/vendors/editorjs/plugins/drag-drop.js",
                    "admin/resources/vendors/editorjs/plugins/undo.js",
                    "admin/resources/vendors/editorjs/editor.js")
            ->addVariableList(["currentCategories" => $currentCategories, "categories" => $categories, "undefinedArticles" => $undefinedArticles, "undefinedCategories" => $undefinedCategories])
            ->view();
    }

    #[Link("/article/add/:cat", Link::POST, ["cat" => "[0-9]+"], "/cmw-admin/wiki", secure: false)]
    public function addArticlePost(int $cat): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");
        
        $articles = new WikiArticlesModel();

        $title = filter_input(INPUT_POST, "title");
        $icon = filter_input(INPUT_POST, "icon");
        $content = filter_input(INPUT_POST, "content");

        $slug = Utils::normalizeForSlug($title);

        //Get the author pseudo
        $user = new UsersModel;
        $userEntity = $user->getUserById($_SESSION['cmwUserId']);
        $userId = $userEntity?->getId();

        $articles->createArticle($title, $cat, $icon, $content, $slug, $userId);
        header("location: ../../list");
        
    }

    #[Link("/article/positionDown/:id/:position", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function positionDown(int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $this->wikiArticlesModel->downPositionArticle($id, $position);

        header("location: ../../../list");
    }

    #[Link("/article/positionUp/:id/:position", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function positionUp(int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $this->wikiArticlesModel->upPositionArticle($id, $position);

        header("location: ../../../list");
    }

    #[Link("/categorie/edit/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function editCategorie(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.edit");

        $categorie = $this->wikiCategoriesModel->getCategorieById($id);

        View::createAdminView('wiki', 'editCategorie')
            ->addVariableList(["categorie" => $categorie])
            ->view();
    }

    #[Link("/categorie/edit/:id", Link::POST, ["id" => "[0-9]+"], "/cmw-admin/wiki", secure: false)]
    #[NoReturn] public function editCategoriePost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.edit");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");
        $slug = filter_input(INPUT_POST, "slug");

        $isDefine = filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) ?? 0;

        $this->wikiCategoriesModel->updateCategorie($id, $name, $description, $slug, $icon, $isDefine);

        header("location: ../../list");
    }

    #[Link("/categorie/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function deleteCategorie(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.delete");

        $this->wikiCategoriesModel->deleteCategorie($id);

        header("location: ../../list");
    }

    #[Link("/article/edit/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function editArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");

        $categories = $this->wikiCategoriesModel->getCategories();
        $article = $this->wikiArticlesModel->getArticleById($id);

        View::createAdminView('wiki', 'editArticle')
            ->addScriptBefore("admin/resources/vendors/editorjs/plugins/header.js",
                    "admin/resources/vendors/editorjs/plugins/image.js",
                    "admin/resources/vendors/editorjs/plugins/delimiter.js",
                    "admin/resources/vendors/editorjs/plugins/list.js",
                    "admin/resources/vendors/editorjs/plugins/quote.js",
                    "admin/resources/vendors/editorjs/plugins/code.js",
                    "admin/resources/vendors/editorjs/plugins/table.js",
                    "admin/resources/vendors/editorjs/plugins/link.js",
                    "admin/resources/vendors/editorjs/plugins/warning.js",
                    "admin/resources/vendors/editorjs/plugins/embed.js",
                    "admin/resources/vendors/editorjs/plugins/marker.js",
                    "admin/resources/vendors/editorjs/plugins/underline.js",
                    "admin/resources/vendors/editorjs/plugins/drag-drop.js",
                    "admin/resources/vendors/editorjs/plugins/undo.js",
                    "admin/resources/vendors/editorjs/editor.js")
            ->addVariableList(["article" => $article, "categories" => $categories])
            ->view();
    }

    #[Link("/article/edit/:id", Link::POST, ["id" => "[0-9]+"], "/cmw-admin/wiki", secure: false)]
    #[NoReturn] public function editArticlePost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");
    Response::sendAlert("success", "test","test");
        //Get the editor id
        $user = new UsersModel;
        $userEntity = $user->getUserById($_SESSION['cmwUserId']);


        $title = filter_input(INPUT_POST, "title");
        $category_id = filter_input(INPUT_POST, "categorie");
        $content = filter_input(INPUT_POST, "content");
        $icon = filter_input(INPUT_POST, "icon");
        $lastEditor = $userEntity?->getId();
        $isDefine = filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) ?? 0;

        $this->wikiArticlesModel->updateArticle($id, $title, $category_id, $content, $icon, $lastEditor, $isDefine);

        header("location: ../../list");
    }

    #[Link("/article/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function deleteArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.delete");

        $this->wikiArticlesModel->deleteArticle($id);

        header("location: ../../list");

    }

    #[Link("/categorie/define/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function defineCategorie(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.define");

        $this->wikiCategoriesModel->defineCategorie($id);

        header("location: ../../list");
    }

    #[Link("/article/define/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function defineArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.define");

        $this->wikiArticlesModel->defineArticle($id);

        header("location: ../../list");
    }



    /* //////////////////// FRONT PUBLIC //////////////////// */

    //List all the categories & articles in the main page
    #[Link("/wiki", Link::GET)]
    public function publicMain(): void
    {
        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        $firstArticle = $this->wikiArticlesModel->getFirstArticle();

        //Include the public view file ("public/themes/$themePath/views/wiki/main.view.php")
        $view = new View('wiki', 'main');
        $view->addScriptBefore("admin/resources/vendors/highlight/highlight.min.js","admin/resources/vendors/highlight/highlightAll.js");
        $view->addStyle("admin/resources/vendors/highlight/style/" . EditorController::getCurrentStyle());
        $view->addVariableList(["categories" => $categories, "article" => null, "firstArticle" => $firstArticle]);
        $view->view();
    }

    /**
     * @throws \CMW\Router\RouterException
     */
    #[Link("/wiki/:slugC/:slugA", Link::GET, ["slugC" => ".*?"])]
    public function publicShowArticle($slugC, $slugA): void
    {
        //get the current url (slug)
        $url = $slugA;

        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        $article = $this->wikiArticlesModel->getArticleBySlug($slugA);
        $firstArticle = $this->wikiArticlesModel->getFirstArticle();

        //Include the public view file ("public/themes/$themePath/views/wiki/main.view.php")
        $view = new View('wiki', 'main');
        $view->addScriptBefore("admin/resources/vendors/highlight/highlight.min.js","admin/resources/vendors/highlight/highlightAll.js");
        $view->addStyle("admin/resources/vendors/highlight/style/" . EditorController::getCurrentStyle());
        $view->addVariableList(["categories" => $categories, "article" => $article, "url" => $url,
            "firstArticle" => $firstArticle]);
        $view->view();
    }
}