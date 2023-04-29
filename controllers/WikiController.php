<?php

namespace CMW\Controller\Wiki;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Core\EditorController;
use CMW\Controller\users\UsersController;
use CMW\Manager\Requests\Request;
use CMW\Model\users\UsersModel;
use CMW\Model\wiki\WikiArticlesModel;
use CMW\Model\wiki\WikiCategoriesModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\Redirect;
use CMW\Utils\Response;
use CMW\Manager\Views\View;
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
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.add");


        View::createAdminView('wiki', 'addCategorie')
            ->view();
    }


    #[Link("/list", Link::POST, [], "/cmw-admin/wiki")]
    public function addCategoriePost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.add");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");

        $slug = Utils::normalizeForSlug(filter_input(INPUT_POST, "slug"));


        $this->wikiCategoriesModel->createCategorie($name, $description, $icon, $slug);
        Redirect::redirectToPreviousPage();
    }

    #[Link("/article/add/:cat", Link::GET, ["cat" => "[0-9]+"], "/cmw-admin/wiki")]
    public function addArticle(Request $request, int $cat): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.add");

        //Get all undefined articles
        $undefinedArticles = $this->wikiArticlesModel->getUndefinedArticles();

        $undefinedCategories = $this->wikiCategoriesModel->getUndefinedCategories();

        $categories = $this->wikiCategoriesModel->getDefinedCategories();
        $currentCategories = $this->wikiCategoriesModel->getCategories();

        View::createAdminView('wiki', 'addArticle')
            ->addScriptBefore("Admin/Resources/Vendors/Editorjs/Plugins/header.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/image.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/delimiter.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/list.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/quote.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/code.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/table.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/link.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/warning.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/embed.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/marker.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/underline.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/drag-drop.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/undo.js",
                    "Admin/Resources/Vendors/Editorjs/editor.js")
            ->addVariableList(["currentCategories" => $currentCategories, "categories" => $categories, "undefinedArticles" => $undefinedArticles, "undefinedCategories" => $undefinedCategories])
            ->view();
    }

    #[Link("/article/add/:cat", Link::POST, ["cat" => "[0-9]+"], "/cmw-admin/wiki", secure: false)]
    public function addArticlePost(Request $request, int $cat): void
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
        
    }

    #[Link("/article/positionDown/:id/:position", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function positionDown(Request $request, int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $this->wikiArticlesModel->downPositionArticle($id, $position);

        Redirect::redirectToPreviousPage();
    }

    #[Link("/article/positionUp/:id/:position", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function positionUp(Request $request, int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $this->wikiArticlesModel->upPositionArticle($id, $position);

        Redirect::redirectToPreviousPage();
    }

    #[Link("/categorie/edit/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function editCategorie(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.edit");

        $categorie = $this->wikiCategoriesModel->getCategorieById($id);

        View::createAdminView('wiki', 'editCategorie')
            ->addVariableList(["categorie" => $categorie])
            ->view();
    }

    #[Link("/categorie/edit/:id", Link::POST, ["id" => "[0-9]+"], "/cmw-admin/wiki", secure: false)]
    #[NoReturn] public function editCategoriePost(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.edit");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");
        $slug = filter_input(INPUT_POST, "slug");

        $isDefine = filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) ?? 0;

        $this->wikiCategoriesModel->updateCategorie($id, $name, $description, $slug, $icon, $isDefine);

        Redirect::redirect("cmw-admin/wiki/list");
    }

    #[Link("/categorie/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function deleteCategorie(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.delete");

        $this->wikiCategoriesModel->deleteCategorie($id);

        Redirect::redirect("cmw-admin/wiki/list");
    }

    #[Link("/article/edit/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function editArticle(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");

        $categories = $this->wikiCategoriesModel->getCategories();
        $article = $this->wikiArticlesModel->getArticleById($id);

        View::createAdminView('wiki', 'editArticle')
            ->addScriptBefore("Admin/Resources/Vendors/Editorjs/Plugins/header.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/image.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/delimiter.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/list.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/quote.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/code.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/table.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/link.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/warning.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/embed.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/marker.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/underline.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/drag-drop.js",
                    "Admin/Resources/Vendors/Editorjs/Plugins/undo.js",
                    "Admin/Resources/Vendors/Editorjs/editor.js")
            ->addVariableList(["article" => $article, "categories" => $categories])
            ->view();
    }

    #[Link("/article/edit/:id", Link::POST, ["id" => "[0-9]+"], "/cmw-admin/wiki", secure: false)]
    #[NoReturn] public function editArticlePost(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");
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

    }

    #[Link("/article/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function deleteArticle(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.delete");

        $this->wikiArticlesModel->deleteArticle($id);

        Redirect::redirect("cmw-admin/wiki/list");

    }

    #[Link("/categorie/define/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function defineCategorie(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.define");

        $this->wikiCategoriesModel->defineCategorie($id);

        Redirect::redirect("cmw-admin/wiki/list");
    }

    #[Link("/article/define/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function defineArticle(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.define");

        $this->wikiArticlesModel->defineArticle($id);

        Redirect::redirect("cmw-admin/wiki/list");
    }



    /* //////////////////// FRONT PUBLIC //////////////////// */

    //List all the categories & articles in the main page
    #[Link("/wiki", Link::GET)]
    public function publicMain(): void
    {
        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        $firstArticle = $this->wikiArticlesModel->getFirstArticle();

        //Include the Public view file ("Public/Themes/$themePath/Views/wiki/main.view.php")
        $view = new View('wiki', 'main');
        $view->addScriptBefore("Admin/Resources/Vendors/highlight/highlight.min.js","Admin/Resources/Vendors/highlight/highlightAll.js");
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css", "Admin/Resources/Vendors/highlight/style/" . EditorController::getCurrentStyle());
        $view->addVariableList(["categories" => $categories, "article" => null, "firstArticle" => $firstArticle]);
        $view->view();
    }

    /**
     * @throws \CMW\Router\RouterException
     */
    #[Link("/wiki/:slugC/:slugA", Link::GET, ["slugC" => ".*?"])]
    public function publicShowArticle(Request $request, $slugC, $slugA): void
    {


        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        $article = $this->wikiArticlesModel->getArticleBySlug($slugA);
        

        $firstArticle = $this->wikiArticlesModel->getFirstArticle();

        //Include the Public view file ("Public/Themes/$themePath/Views/wiki/main.view.php")
        $view = new View('wiki', 'main');
        $view->addScriptBefore("Admin/Resources/Vendors/highlight/highlight.min.js","Admin/Resources/Vendors/highlight/highlightAll.js");
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css", "Admin/Resources/Vendors/highlight/style/" . EditorController::getCurrentStyle());
        $view->addVariableList(["categories" => $categories, "article" => $article,
            "firstArticle" => $firstArticle]);

        $view->view();
    }
}