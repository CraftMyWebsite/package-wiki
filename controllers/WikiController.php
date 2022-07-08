<?php

namespace CMW\Controller\Wiki;

use CMW\Controller\CoreController;
use CMW\Controller\Menus\MenusController;
use CMW\Controller\users\UsersController;
use CMW\Model\wiki\WikiCategoriesModel;
use CMW\Model\wiki\WikiArticlesModel;
use CMW\Model\users\UsersModel;


/**
 * Class: @wikiController
 * @package wiki
 * @author Teyir
 * @version 1.0
 */
class WikiController extends CoreController
{

    public static string $themePath;

    public function __construct($themePath = null)
    {
        parent::__construct($themePath);
    }

    public function frontWikiListAdmin(): void
    {
        UsersController::isUserHasPermission("wiki.show");

        $articles = new WikiArticlesModel();

        $categories = new WikiCategoriesModel();

        //Get all undefined articles
        $undefinedArticles = $articles->getUndefinedArticles();

        $undefinedCategories = $categories->getUndefinedCategories();

        $getAllCategories = $categories->getAllCategories();

        $includes = array(
            "styles" => [
                "app/package/wiki/views/assets/css/main.css"
            ]
        );

        //Include the view file ("views/list.admin.view.php").
        view('wiki', 'list.admin', ["articles" => $articles, "categories" => $categories,
            "undefinedArticles" => $undefinedArticles, "undefinedCategories" => $undefinedCategories,
            "getAllCategories" => $getAllCategories], 'admin', $includes);
    }

    public function addCategorie(): void
    {
        UsersController::isUserHasPermission("wiki.categorie.add");

        $includes = array(
            "styles" => [
                "app/package/wiki/views/assets/css/main.css"
            ]
        );

        view('wiki', 'addCategorie.admin', [], 'admin', $includes);
    }


    public function addCategoriePost(): void
    {
        UsersController::isUserHasPermission("wiki.categorie.add");

        $categories = new WikiCategoriesModel();

        $categories->name = filter_input(INPUT_POST, "name");
        $categories->description = filter_input(INPUT_POST, "description");
        $categories->icon = filter_input(INPUT_POST, "icon");

        $categories->slug = $categories->cleanString(filter_input(INPUT_POST, "slug"));;


        $categories->create();
        header("location: ../list");
    }

    public function addArticle(): void
    {
        UsersController::isUserHasPermission("wiki.article.add");

        $articles = new WikiArticlesModel();

        $categories = new WikiCategoriesModel();
        $categories = $categories->fetchAll();

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

        view('wiki', 'addArticle.admin', ["articles" => $articles, "categories" => $categories], 'admin', $includes);
    }

    public function addArticlePost(): void
    {
        UsersController::isUserHasPermission("wiki.article.add");

        $articles = new WikiArticlesModel();

        $articles->title = filter_input(INPUT_POST, "title");
        $articles->categoryId = filter_input(INPUT_POST, "categorie");
        $articles->icon = filter_input(INPUT_POST, "icon");
        $articles->content = filter_input(INPUT_POST, "content");

        $articles->slug = $articles->cleanString($articles->title);

        //Get the author pseudo
        $user = new UsersModel();
        $user->fetch($_SESSION['cmwUserId']);
        $articles->author = $user->userPseudo;

        $articles->create();
        header("location: ../list");
    }

    public function editCategorie($id): void
    {
        UsersController::isUserHasPermission("wiki.categorie.edit");

        $categories = new WikiCategoriesModel();
        $categories->id = $id;

        $categories->fetch($id);

        $includes = array(
            "styles" => [
                "app/package/wiki/views/assets/css/main.css"
            ]
        );


        view('wiki', 'editCategorie.admin', ["categories" => $categories], 'admin', $includes);
    }

    public function editCategoriePost($id): void
    {
        UsersController::isUserHasPermission("wiki.categorie.edit");

        $categories = new WikiCategoriesModel();
        $categories->id = $id;
        $categories->name = filter_input(INPUT_POST, "name");
        $categories->description = filter_input(INPUT_POST, "description");
        $categories->icon = filter_input(INPUT_POST, "icon");
        $categories->slug = filter_input(INPUT_POST, "slug");
        if (filter_input(INPUT_POST, "isDefine") == null) {
            $categories->isDefine = 0;
        } else {
            $categories->isDefine = filter_input(INPUT_POST, "isDefine");
        }

        $categories->update();

        header("location: ../../list");
        die();

    }

    public function deleteCategorie($id): void
    {
        UsersController::isUserHasPermission("wiki.categorie.delete");

        $categorie = new WikiCategoriesModel();
        $categorie->id = $id;
        $categorie->delete();

        header("location: ../../list");

    }

    public function editArticle($id): void
    {
        UsersController::isUserHasPermission("wiki.article.edit");

        $articles = new WikiArticlesModel();
        $articles->id = $id;

        $categories = new WikiCategoriesModel();
        $categories = $categories->fetchAll();

        $articles->fetch($id);

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

        view('wiki', 'editArticle.admin', ["articles" => $articles,
            "categories" => $categories], 'admin', $includes);
    }

    public function editArticlePost($id): void
    {
        UsersController::isUserHasPermission("wiki.article.edit");

        //Get the author pseudo
        $user = new UsersModel();
        $user->fetch($_SESSION['cmwUserId']);

        $articles = new WikiArticlesModel();

        $articles->id = $id;
        $articles->title = filter_input(INPUT_POST, "title");
        $articles->content = filter_input(INPUT_POST, "content");
        $articles->icon = filter_input(INPUT_POST, "icon");
        $articles->lastEditor = $user->userPseudo;
        if (filter_input(INPUT_POST, "isDefine") == null) {
            $articles->isDefine = 0;
        } else {
            $articles->isDefine = filter_input(INPUT_POST, "isDefine");
        }

        $articles->update();

        header("location: ../../list");
        die();

    }

    public function deleteArticle($id): void
    {
        UsersController::isUserHasPermission("wiki.article.delete");

        $article = new WikiArticlesModel();
        $article->id = $id;
        $article->delete();

        header("location: ../../list");

    }

    public function defineCategorie($id): void
    {
        UsersController::isUserHasPermission("wiki.categorie.define");

        $categorie = new WikiCategoriesModel();
        $categorie->id = $id;
        $categorie->define();

        header("location: ../../list");
    }

    public function defineArticle($id): void
    {
        UsersController::isUserHasPermission("wiki.article.define");

        $article = new WikiArticlesModel();
        $article->id = $id;
        $article->define();

        header("location: ../../list");
    }



    /* //////////////////// FRONT PUBLIC //////////////////// */

    //List all the categories & articles in the main page
    public function publicMain(): void
    {

        //Default controllers (important)
        $core = new CoreController();
        $menu = new MenusController();

        $categorie = new WikiCategoriesModel();
        $getAllCategories = $categorie->getAllCategories();

        $articles = new WikiArticlesModel();


        //Include the public view file ("public/themes/$themePath/views/wiki/main.view.php")
        view('wiki', 'main', ["categorie" => $categorie, "getAllCategories" => $getAllCategories,
            "articles" => $articles, "core" => $core, "menu" => $menu], 'public', []);
    }

    public function publicShowArticle($slugC, $slugA): void
    {

        //get the current url (slug)
        $url = $slugA;

        //Default controllers (important)
        $core = new CoreController();
        $menu = new MenusController();

        $categorie = new WikiCategoriesModel();
        $getAllCategories = $categorie->getAllCategories();

        $articles = new WikiArticlesModel();

        $articles->getContent($slugA);


        //Include the public view file ("public/themes/$themePath/views/wiki/main.view.php")
        view('wiki', 'main', ["categorie" => $categorie, "getAllCategories" => $getAllCategories,
            "articles" => $articles, "url" => $url, "core" => $core, "menu" => $menu], 'public', []);
    }
}