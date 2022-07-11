<?php

namespace CMW\Controller\Wiki;

use CMW\Controller\CoreController;
use CMW\Controller\Menus\MenusController;
use CMW\Controller\users\UsersController;
use CMW\Model\wiki\WikiCategoriesModel;
use CMW\Model\wiki\WikiArticlesModel;
use CMW\Model\users\UsersModel;
use CMW\Utils\Utils;
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

    public function frontWikiListAdmin(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.show");


        //Get all undefined articles
        $undefinedArticles = $this->wikiArticlesModel->getUndefinedArticles();

        $undefinedCategories = $this->wikiCategoriesModel->getUndefinedCategories();

        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        $includes = array(
            "styles" => [
                "app/package/wiki/views/assets/css/main.css"
            ]
        );

        //Include the view file ("views/list.admin.view.php").
        view('wiki', 'list.admin', ["categories" => $categories, "undefinedArticles" => $undefinedArticles,
            "undefinedCategories" => $undefinedCategories], 'admin', $includes);
    }

    public function addCategorie(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.add");

        $includes = array(
            "styles" => [
                "app/package/wiki/views/assets/css/main.css"
            ]
        );

        view('wiki', 'addCategorie.admin', [], 'admin', $includes);
    }


    public function addCategoriePost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.add");

        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
        $icon = filter_input(INPUT_POST, "icon", FILTER_SANITIZE_STRING);

        $slug = Utils::normalizeForSlug(filter_input(INPUT_POST, "slug", FILTER_SANITIZE_STRING));


        $this->wikiCategoriesModel->createCategorie($name, $description, $icon, $slug);
        header("location: ../list");
    }

    public function addArticle(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $categories = $this->wikiCategoriesModel->getCategories();

        $includes = array(
            "scripts" => [
                "before" => [
                    "admin/resources/vendors/summernote/summernote.min.js",
                    "admin/resources/vendors/summernote/summernote-bs4.min.js",
                    "app/package/wiki/views/assets/js/summernoteInit.js"
                ]
            ],
            "styles" => [
                "app/package/wiki/views/assets/css/main.css",
                "admin/resources/vendors/summernote/summernote-bs4.min.css",
                "admin/resources/vendors/summernote/summernote.min.css"
            ]
        );

        view('wiki', 'addArticle.admin', ["categories" => $categories], 'admin', $includes);
    }

    public function addArticlePost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        $articles = new WikiArticlesModel();

        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
        $categoryId = filter_input(INPUT_POST, "categorie", FILTER_SANITIZE_NUMBER_INT);
        $icon = filter_input(INPUT_POST, "icon", FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, "content");

        $slug = Utils::normalizeForSlug($title);

        //Get the author pseudo
        $user = new UsersModel;
        $userEntity = $user->getUserById($_SESSION['cmwUserId']);
        $userId = $userEntity->getId();

        $articles->createArticle($title, $categoryId, $icon, $content, $slug, $userId);
        header("location: ../list");
    }

    public function editCategorie(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.edit");

        $categorie = $this->wikiCategoriesModel->getCategorieById($id);

        $includes = array(
            "styles" => [
                "app/package/wiki/views/assets/css/main.css"
            ]
        );


        view('wiki', 'editCategorie.admin', ["categorie" => $categorie], 'admin', $includes);
    }

    #[NoReturn] public function editCategoriePost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.edit");

        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
        $icon = filter_input(INPUT_POST, "icon", FILTER_SANITIZE_STRING);
        $slug = filter_input(INPUT_POST, "slug", FILTER_SANITIZE_STRING);

        if (filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) == null) {
            $isDefine = 0;
        } else {
            $isDefine = filter_input(INPUT_POST, "isDefine");
        }

        $this->wikiCategoriesModel->updateCategorie($id, $name, $description, $slug, $icon, $isDefine);

        header("location: ../../list");
        die();

    }

    public function deleteCategorie($id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.delete");

        $this->wikiCategoriesModel->deleteCategorie($id);

        header("location: ../../list");

    }

    public function editArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");


        $categories = $this->wikiCategoriesModel->getCategories();

        $article = $this->wikiArticlesModel->getArticleById($id);

        $includes = array(
            "scripts" => [
                "before" => [
                    "admin/resources/vendors/summernote/summernote.min.js",
                    "admin/resources/vendors/summernote/summernote-bs4.min.js",
                    "app/package/wiki/views/assets/js/summernoteInit.js"
                ]
            ],
            "styles" => [
                "app/package/wiki/views/assets/css/main.css",
                "admin/resources/vendors/summernote/summernote-bs4.min.css",
                "admin/resources/vendors/summernote/summernote.min.css"
            ]
        );

        view('wiki', 'editArticle.admin', ["article" => $article,
            "categories" => $categories], 'admin', $includes);
    }

    #[NoReturn] public function editArticlePost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");

        //Get the editor id
        $user = new UsersModel;
        $userEntity = $user->getUserById($_SESSION['cmwUserId']);


        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, "content");
        $icon = filter_input(INPUT_POST, "icon", FILTER_SANITIZE_STRING);
        $lastEditor = $userEntity->getId();
        if (filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) == null) {
            $isDefine = 0;
        } else {
            $isDefine = filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT);
        }

        $this->wikiArticlesModel->updateArticle($id, $title, $content, $icon, $lastEditor, $isDefine);

        header("location: ../../list");
        die();
    }

    public function deleteArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.delete");

        $this->wikiArticlesModel->deleteArticle($id);

        header("location: ../../list");

    }

    public function defineCategorie(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.categorie.define");

        $this->wikiCategoriesModel->defineCategorie($id);

        header("location: ../../list");
    }

    public function defineArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.define");

        $this->wikiArticlesModel->defineArticle($id);

        header("location: ../../list");
    }



    /* //////////////////// FRONT PUBLIC //////////////////// */

    //List all the categories & articles in the main page
    public function publicMain(): void
    {

        //Default controllers (important)
        $core = new CoreController();
        $menu = new MenusController();

        $categories = $this->wikiCategoriesModel->getDefinedCategories();

        //Include the public view file ("public/themes/$themePath/views/wiki/main.view.php")
        view('wiki', 'main', ["categories" => $categories, "core" => $core, "menu" => $menu], 'public', []);
    }

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
        view('wiki', 'main', ["categories" => $categories,
            "article" => $article, "url" => $url, "core" => $core, "menu" => $menu], 'public', []);
    }
}