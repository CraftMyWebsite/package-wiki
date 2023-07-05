<?php

namespace CMW\Controller\Wiki;

use CMW\Controller\Core\EditorController;
use CMW\Controller\users\UsersController;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Requests\Request;
use CMW\Model\users\UsersModel;
use CMW\Model\wiki\WikiArticlesModel;
use CMW\Model\wiki\WikiCategoriesModel;
use CMW\Manager\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\Redirect;
use CMW\Manager\Views\View;
use JetBrains\PhpStorm\NoReturn;


/**
 * Class: @wikiController
 * @package wiki
 * @author Teyir
 * @version 1.0
 */
class WikiController extends AbstractController
{
    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/wiki")]
    #[Link("/list", Link::GET, [], "/cmw-admin/wiki")]
    private function frontWikiListAdmin(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.show");


        //Get all undefined articles
        $undefinedArticles = wikiArticlesModel::getInstance()->getUndefinedArticles();

        $undefinedCategories = wikiCategoriesModel::getInstance()->getUndefinedCategories();

        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();
        $currentCategories = wikiCategoriesModel::getInstance()->getCategories();


        //Include the view file ("views/list.admin.view.php").

        View::createAdminView('Wiki', 'list')
            ->addVariableList(["currentCategories" => $currentCategories, "categories" => $categories, "undefinedArticles" => $undefinedArticles, "undefinedCategories" => $undefinedCategories])
            ->view();
    }

    #[Link("/list", Link::GET, [], "/cmw-admin/wiki")]
    private function addCategorie(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.add");


        View::createAdminView('Wiki', 'addCategorie')
            ->view();
    }


    #[Link("/list", Link::POST, [], "/cmw-admin/wiki")]
    private function addCategoriePost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.add");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");

        $slug = Utils::normalizeForSlug(filter_input(INPUT_POST, "slug"));


        wikiCategoriesModel::getInstance()->createCategorie($name, $description, ($icon === "" ? null : $icon), $slug);
        Redirect::redirectPreviousRoute();
    }

    #[Link("/article/add/:cat", Link::GET, ["cat" => "[0-9]+"], "/cmw-admin/wiki")]
    private function addArticle(Request $request, int $cat): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.add");

        //Get all undefined articles
        $undefinedArticles = wikiArticlesModel::getInstance()->getUndefinedArticles();

        $undefinedCategories = wikiCategoriesModel::getInstance()->getUndefinedCategories();

        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();
        $currentCategories = wikiCategoriesModel::getInstance()->getCategories();

        View::createAdminView('Wiki', 'addArticle')
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
    private function addArticlePost(Request $request, int $cat): void
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

        $articles->createArticle($title, $cat, ($icon === "" ? null : $icon), $content, $slug, $userId);
        
    }

    #[Link("/article/positionDown/:id/:position", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function positionDown(Request $request, int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        wikiArticlesModel::getInstance()->downPositionArticle($id, $position);

        Redirect::redirectPreviousRoute();
    }

    #[Link("/article/positionUp/:id/:position", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function positionUp(Request $request, int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.add");

        wikiArticlesModel::getInstance()->upPositionArticle($id, $position);

        Redirect::redirectPreviousRoute();
    }

    #[Link("/categorie/edit/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function editCategorie(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.edit");

        $categorie = wikiCategoriesModel::getInstance()->getCategorieById($id);

        View::createAdminView('Wiki', 'editCategorie')
            ->addVariableList(["categorie" => $categorie])
            ->view();
    }

    #[Link("/categorie/edit/:id", Link::POST, ["id" => "[0-9]+"], "/cmw-admin/wiki", secure: false)]
    #[NoReturn] private function editCategoriePost(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.edit");

        $name = filter_input(INPUT_POST, "name");
        $description = filter_input(INPUT_POST, "description");
        $icon = filter_input(INPUT_POST, "icon");
        $slug = filter_input(INPUT_POST, "slug");

        $isDefine = filter_input(INPUT_POST, "isDefine", FILTER_SANITIZE_NUMBER_INT) ?? 0;

        wikiCategoriesModel::getInstance()->updateCategorie($id, $name, $description, $slug, ($icon === "" ? null : $icon), $isDefine);

        Redirect::redirect("cmw-admin/wiki/list");
    }

    #[Link("/categorie/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function deleteCategorie(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.delete");

        wikiCategoriesModel::getInstance()->deleteCategorie($id);

        Redirect::redirect("cmw-admin/wiki/list");
    }

    #[Link("/article/edit/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function editArticle(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.edit");

        $categories = wikiCategoriesModel::getInstance()->getCategories();
        $article = wikiArticlesModel::getInstance()->getArticleById($id);

        View::createAdminView('Wiki', 'editArticle')
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
    #[NoReturn] private function editArticlePost(Request $request, int $id): void
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

        wikiArticlesModel::getInstance()->updateArticle($id, $title, $category_id, $content, ($icon === "" ? null : $icon), $lastEditor, $isDefine);

    }

    #[Link("/article/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function deleteArticle(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.delete");

        wikiArticlesModel::getInstance()->deleteArticle($id);

        Redirect::redirect("cmw-admin/wiki/list");

    }

    #[Link("/categorie/define/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function defineCategorie(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.category.define");

        wikiCategoriesModel::getInstance()->defineCategorie($id);

        Redirect::redirect("cmw-admin/wiki/list");
    }

    #[Link("/article/define/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/wiki")]
    private function defineArticle(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "wiki.article.define");

        wikiArticlesModel::getInstance()->defineArticle($id);

        Redirect::redirect("cmw-admin/wiki/list");
    }



    /* //////////////////// FRONT PUBLIC //////////////////// */

    //List all the categories & articles in the main page
    #[Link("/wiki", Link::GET)]
    private function publicMain(): void
    {
        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();

        $firstArticle = wikiArticlesModel::getInstance()->getFirstArticle();

        //Include the Public view file ("Public/Themes/$themePath/Views/Wiki/main.view.php")
        $view = new View('Wiki', 'main');
        $view->addScriptBefore("Admin/Resources/Vendors/Highlight/highlight.min.js","Admin/Resources/Vendors/Highlight/highlightAll.js");
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css", "Admin/Resources/Vendors/Highlight/Style/" . EditorController::getCurrentStyle());
        $view->addVariableList(["categories" => $categories, "article" => null, "firstArticle" => $firstArticle]);
        $view->view();
    }

    /**
     * @throws \CMW\Router\RouterException
     */
    #[Link("/wiki/:slugC/:slugA", Link::GET, ["slugC" => ".*?"])]
    private function publicShowArticle(Request $request, $slugC, $slugA): void
    {


        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();

        $article = wikiArticlesModel::getInstance()->getArticleBySlug($slugA);
        

        $firstArticle = wikiArticlesModel::getInstance()->getFirstArticle();

        //Include the Public view file ("Public/Themes/$themePath/Views/Wiki/main.view.php")
        $view = new View('Wiki', 'main');
        $view->addScriptBefore("Admin/Resources/Vendors/Highlight/highlight.min.js","Admin/Resources/Vendors/Highlight/highlightAll.js");
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css", "Admin/Resources/Vendors/Highlight/Style/" . EditorController::getCurrentStyle());
        $view->addVariableList(["categories" => $categories, "article" => $article,
            "firstArticle" => $firstArticle]);

        $view->view();
    }
}