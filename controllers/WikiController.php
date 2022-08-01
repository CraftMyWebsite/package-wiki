<?php

namespace CMW\Controller\Wiki;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Menus\MenusController;
use CMW\Controller\users\UsersController;
use CMW\Model\users\UsersModel;
use CMW\Model\wiki\WikiArticlesModel;
use CMW\Model\wiki\WikiCategoriesModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
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


        //Include the view file ("views/list.admin.view.php").

        View::createAdminView('wiki', 'list')
            ->addStyle("app/package/wiki/views/assets/css/main.css")
            ->addVariableList(["categories" => $categories, "undefinedArticles" => $undefinedArticles, "undefinedCategories" => $undefinedCategories])
            ->view();
    }

    #[Link("/categorie/add", Link::GET, [], "/cmw-admin/wiki")]
    public function addCategorie(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.add");


        View::createAdminView('wiki', 'addCategorie')
            ->addStyle("app/package/wiki/views/assets/css/main.css")
            ->view();
    }


    #[Link("/categorie/add", Link::POST, [], "/cmw-admin/wiki")]
    public function addCategoriePost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.add");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");

        $slug = Utils::normalizeForSlug(filter_input(INPUT_POST, "slug"));


        $this->wikiCategoriesModel->createCategorie($name, $description, $icon, $slug);
        header("location: ../list");
    }

    #[Link("/article/add", Link::GET, [], "/cmw-admin/wiki")]
    public function addArticle(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $categories = $this->wikiCategoriesModel->getCategories();

        View::createAdminView('wiki', 'addArticle')
            ->addScriptBefore("admin/resources/vendors/summernote/summernote.min.js",
                "admin/resources/vendors/summernote/summernote-bs4.min.js",
                "app/package/wiki/views/assets/js/summernoteInit.js")
            ->addStyle("app/package/wiki/views/assets/css/main.css",
                "admin/resources/vendors/summernote/summernote-bs4.min.css",
                "admin/resources/vendors/summernote/summernote.min.css")
            ->addVariableList(["categories" => $categories])
            ->view();
    }

    #[Link("/article/add", Link::POST, [], "/cmw-admin/wiki")]
    public function addArticlePost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $articles = new WikiArticlesModel();

        $title = filter_input(INPUT_POST, "title");
        $categoryId = filter_input(INPUT_POST, "categorie");
        $icon = filter_input(INPUT_POST, "icon");
        $content = filter_input(INPUT_POST, "content");

        $slug = Utils::normalizeForSlug($title);

        //Get the author pseudo
        $user = new UsersModel;
        $userEntity = $user->getUserById($_SESSION['cmwUserId']);
        $userId = $userEntity->getId();

        $articles->createArticle($title, $categoryId, $icon, $content, $slug, $userId);
        header("location: ../list");
    }

    #[Link("/categorie/edit/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    public function editCategorie(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.edit");

        $categorie = $this->wikiCategoriesModel->getCategorieById($id);

        View::createAdminView('wiki', 'editCategorie')
            ->addStyle("app/package/wiki/views/assets/css/main.css")
            ->addVariableList(["categorie" => $categorie])
            ->view();
    }

    #[Link("/categorie/edit/:id", Link::POST, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    #[NoReturn] public function editCategoriePost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.edit");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");
        $slug = filter_input(INPUT_POST, "slug");

        if (filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) == null) {
            $isDefine = 0;
        } else {
            $isDefine = filter_input(INPUT_POST, "isDefine");
        }

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
            ->addScriptBefore("admin/resources/vendors/summernote/summernote.min.js",
                "admin/resources/vendors/summernote/summernote-bs4.min.js",
                "app/package/wiki/views/assets/js/summernoteInit.js")
            ->addStyle("app/package/wiki/views/assets/css/main.css",
                "admin/resources/vendors/summernote/summernote-bs4.min.css",
                "admin/resources/vendors/summernote/summernote.min.css")
            ->addVariableList(["article" => $article, "categories" => $categories])
            ->view();
    }

    #[Link("/article/edit/:id", Link::POST, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    #[NoReturn] public function editArticlePost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");

        //Get the editor id
        $user = new UsersModel;
        $userEntity = $user->getUserById($_SESSION['cmwUserId']);


        $title = filter_input(INPUT_POST, "title");
        $content = filter_input(INPUT_POST, "content");
        $icon = filter_input(INPUT_POST, "icon");
        $lastEditor = $userEntity->getId();
        if (filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) == null) {
            $isDefine = 0;
        } else {
            $isDefine = filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT);
        }

        $this->wikiArticlesModel->updateArticle($id, $title, $content, $icon, $lastEditor, $isDefine);

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

        //Default controllers (important)
        $core = new CoreController();
        $menu = new MenusController();

        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        //Include the public view file ("public/themes/$themePath/views/wiki/main.view.php")
        $view = new View('wiki', 'main');
        $view->addVariableList(["categories" => $categories, "core" => $core, "menu" => $menu]);
    }

    #[Link("/wiki/:slugC/:slugA", Link::GET, ["slugC" => ".*?"])]
    public function publicShowArticle($slugC, $slugA): void
    {
        //get the current url (slug)
        $url = $slugA;

        //Default controllers (important)
        $core = new CoreController();
        $menu = new MenusController();

        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        $article = $this->wikiArticlesModel->getArticleBySlug($slugA);


        //Include the public view file ("public/themes/$themePath/views/wiki/main.view.php")
        $view = new View('wiki', 'main');
        $view->addVariableList(["categories" => $categories, "article" => $article, "url" => $url, "core" => $core, "menu" => $menu]);
        $view->view();
    }
}