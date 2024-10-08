<?php

namespace CMW\Controller\Wiki;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Filter\FilterManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Users\UsersModel;
use CMW\Model\Wiki\WikiArticlesModel;
use CMW\Model\Wiki\WikiCategoriesModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class: @wikiController
 * @package wiki
 * @author Teyir
 * @version 0.0.1
 */
class WikiController extends AbstractController
{
    #[Link(path: '/', method: Link::GET, scope: '/cmw-admin/wiki')]
    private function frontWikiListAdmin(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.show');

        // Get all undefined articles
        $undefinedArticles = wikiArticlesModel::getInstance()->getUndefinedArticles();

        $undefinedCategories = wikiCategoriesModel::getInstance()->getUndefinedCategories();

        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();
        $currentCategories = wikiCategoriesModel::getInstance()->getCategories();

        View::createAdminView('Wiki', 'main')
            ->addVariableList(['currentCategories' => $currentCategories, 'categories' => $categories,
                'undefinedArticles' => $undefinedArticles, 'undefinedCategories' => $undefinedCategories])
            ->view();
    }

    #[NoReturn] #[Link('/', Link::POST, [], '/cmw-admin/wiki')]
    private function addCategoryPost(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.category.add');

        $name = FilterManager::filterInputStringPost('name');
        $description = FilterManager::filterInputStringPost('description');
        $icon = FilterManager::filterInputStringPost('icon', 35);
        $slug = FilterManager::filterInputStringPost('slug');

        if (empty($slug)) {
            $slug = Utils::normalizeForSlug($name);
        }

        wikiCategoriesModel::getInstance()->createCategorie($name, $description, ($icon === '' ? null : $icon), $slug);

        Redirect::redirectPreviousRoute();
    }

    #[Link('/article/add/:cat', Link::GET, ['cat' => '[0-9]+'], '/cmw-admin/wiki')]
    private function addArticle(int $cat): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.category.add');

        // Get all undefined articles
        $undefinedArticles = wikiArticlesModel::getInstance()->getUndefinedArticles();

        $undefinedCategories = wikiCategoriesModel::getInstance()->getUndefinedCategories();

        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();
        $currentCategories = wikiCategoriesModel::getInstance()->getCategories();

        View::createAdminView('Wiki', 'addArticle')
            ->addScriptBefore('Admin/Resources/Vendors/Tinymce/tinymce.min.js',
                'Admin/Resources/Vendors/Tinymce/Config/full.js')
            ->addVariableList(['currentCategories' => $currentCategories, 'categories' =>
                $categories, 'undefinedArticles' => $undefinedArticles, 'undefinedCategories' => $undefinedCategories])
            ->view();
    }

    #[NoReturn] #[Link('/article/add/:cat', Link::POST, ['cat' => '[0-9]+'], '/cmw-admin/wiki')]
    private function addArticlePost(int $cat): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.article.add');

        $articles = new WikiArticlesModel();

        $title = FilterManager::filterInputStringPost('title');
        $icon = FilterManager::filterInputStringPost('icon', 35);
        $content = FilterManager::filterInputStringPost('content', null);

        $slug = Utils::normalizeForSlug($title);

        // Get the author pseudo
        $user = UsersModel::getCurrentUser()?->getId();

        $articles->createArticle($title, $cat, ($icon === '' ? null : $icon), $content, $slug, $user);

        Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('wiki.alert.added'));

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link('/article/positionDown/:id/:position', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function positionDown(int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.article.add');

        wikiArticlesModel::getInstance()->downPositionArticle($id, $position);

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link('/article/positionUp/:id/:position', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function positionUp(int $id, int $position): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.article.add');

        wikiArticlesModel::getInstance()->upPositionArticle($id, $position);

        Redirect::redirectPreviousRoute();
    }

    #[Link('/category/edit/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function editCategory(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.category.edit');

        $category = wikiCategoriesModel::getInstance()->getCategoryById($id);

        View::createAdminView('Wiki', 'editCategory')
            ->addVariableList(['category' => $category])
            ->view();
    }

    #[Link('/category/edit/:id', Link::POST, ['id' => '[0-9]+'], '/cmw-admin/wiki', secure: false)]
    #[NoReturn]
    private function editCategoryPost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.category.edit');

        $name = FilterManager::filterInputStringPost("name");
        $description = FilterManager::filterInputStringPost("description");
        $icon = FilterManager::filterInputStringPost("icon", 35);
        $slug = FilterManager::filterInputStringPost("slug");

        $isDefine = filter_input(INPUT_POST, 'isDefine', FILTER_SANITIZE_NUMBER_INT) ?? 0;

        wikiCategoriesModel::getInstance()->updateCategorie($id, $name, $description, $slug, ($icon === '' ? null : $icon), $isDefine);

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn]
    #[Link('/categorie/delete/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function deleteCategory(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.category.delete');

        wikiCategoriesModel::getInstance()->deleteCategorie($id);

        Redirect::redirectPreviousRoute();
    }

    #[Link('/article/edit/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function editArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.article.edit');

        $categories = wikiCategoriesModel::getInstance()->getCategories();
        $article = wikiArticlesModel::getInstance()->getArticleById($id);

        View::createAdminView('Wiki', 'editArticle')
            ->addScriptBefore('Admin/Resources/Vendors/Tinymce/tinymce.min.js',
                'Admin/Resources/Vendors/Tinymce/Config/full.js')
            ->addVariableList(['article' => $article, 'categories' => $categories])
            ->view();
    }

    #[Link('/article/edit/:id', Link::POST, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    #[NoReturn]
    private function editArticlePost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.article.edit');

        $title = FilterManager::filterInputStringPost('title');
        $icon = FilterManager::filterInputStringPost('icon', 35);
        $content = FilterManager::filterInputStringPost('content', null);
        $categoryId = FilterManager::filterInputIntPost('category');
        $user = UsersModel::getCurrentUser()?->getId();
        $isDefine = filter_input(INPUT_POST, 'isDefine', FILTER_SANITIZE_NUMBER_INT) ?? 0;

        wikiArticlesModel::getInstance()->updateArticle(
            $id,
            $title,
            $categoryId,
            $content,
            ($icon === '' ? null : $icon),
            $user,
            $isDefine,
        );

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('core.toaster.success'),
            LangManager::translate('wiki.alert.edited')
        );

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link('/article/delete/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function deleteArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.article.delete');

        wikiArticlesModel::getInstance()->deleteArticle($id);

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link('/categorie/define/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function defineCategorie(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.category.define');

        wikiCategoriesModel::getInstance()->defineCategorie($id);

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link('/article/define/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/wiki')]
    private function defineArticle(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'wiki.article.define');

        wikiArticlesModel::getInstance()->defineArticle($id);

        Redirect::redirectPreviousRoute();
    }

    /* //////////////////// FRONT PUBLIC //////////////////// */

    // List all the categories & articles in the main page
    #[Link('/wiki', Link::GET)]
    private function publicMain(): void
    {
        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();

        $firstArticle = wikiArticlesModel::getInstance()->getFirstArticle();

        // Include the Public view file ("Public/Themes/$themePath/Views/Wiki/main.view.php")
        $view = new View('Wiki', 'main');
        $view->addScriptBefore('Admin/Resources/Vendors/Prismjs/prism.js');
        $view->addStyle('Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css');
        $view->addVariableList(['categories' => $categories, 'article' => null, 'firstArticle' => $firstArticle]);
        $view->view();
    }

    #[Link('/wiki/:slugC/:slugA', Link::GET, ['slugC' => '.*?'])]
    private function publicShowArticle($slugC, $slugA): void
    {
        $categories = wikiCategoriesModel::getInstance()->getDefinedCategories();

        $article = wikiArticlesModel::getInstance()->getArticleBySlug($slugA);

        $firstArticle = wikiArticlesModel::getInstance()->getFirstArticle();

        // Include the Public view file ("Public/Themes/$themePath/Views/Wiki/main.view.php")
        $view = new View('Wiki', 'main');
        $view->addScriptBefore('Admin/Resources/Vendors/Prismjs/prism.js');
        $view->addStyle('Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css');
        $view->addVariableList(['categories' => $categories, 'article' => $article,
            'firstArticle' => $firstArticle]);

        $view->view();
    }
}
