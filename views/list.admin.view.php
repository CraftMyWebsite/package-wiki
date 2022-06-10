<?php
$title = WIKI_DASHBOARD_TITLE;
$description = WIKI_DASHBOARD_DESC;

$styles = '<link rel="stylesheet" href="' . getenv("PATH_SUBFOLDER") . 'app/package/wiki/views/ressources/css/main.css">';

/** @var wikiArticlesModel[] $articles */
/** @var wikiCategoriesModel[] $categories */

ob_start();
?>


    <div class="container">
        <section class="card">
            <div class="row p-3">
                <div class="mx-auto">
                    <a href="add/categorie" class="btn btn-success"><?= WIKI_DASHBOARD_BUTTON_ADD_CATEGORY ?></a>
                </div>

                <div class="mx-auto">
                    <a href="add/article" class="btn btn-success"><?= WIKI_DASHBOARD_BUTTON_ADD_ARTICLE ?></a>
                </div>
            </div>
        </section>
        <div class="row">

            <section class="content pb-3">
                <div class="container h-100">
                    <div class="card card-row card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?= WIKI_DASHBOARD_ARTICLES_UNDEFINED ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Undefined Articles-->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="card-title"><?= WIKI_DASHBOARD_ARTICLES ?></h5>
                                    <div class="card-tools">
                                        <span>(<strong><?= $articles->getNumberOfUndefinedArticles() ?></strong>)</span>
                                    </div>
                                </div>
                                <div class="card-body" id="list-articles">
                                    <ul class="list-unstyled">
                                        <?php
                                        /** @var wikiArticlesModel[] $undefinedArticles */
                                        foreach ($undefinedArticles as $undefinedArticle):?>
                                            <li><?= $undefinedArticle['title'] ?>
                                                <div class="float-right">
                                                    <a href="define/article/<?= $undefinedArticle['id'] ?>"
                                                       class="icon-add"><i class="fas fa-plus-circle"></i></a>
                                                    <a href="delete/article/<?= $undefinedArticle['id'] ?>"
                                                       class="icon-delete"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            </li>

                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Undefined Categories-->
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="card-title"><?= WIKI_DASHBOARD_CATEGORIES ?></h5>
                                    <div class="card-tools">
                                        <span>(<strong><?= $categories->getNumberOfUndefinedCategories() ?></strong>)</span>
                                    </div>
                                </div>
                                <div class="card-body" id="list-articles">
                                    <ul class="list-unstyled">
                                        <?php
                                        /** @var wikiCategoriesModel[] $undefinedCategories */
                                        foreach ($undefinedCategories as $undefinedCategorie):?>
                                            <li><?= $undefinedCategorie['name'] ?>
                                                <div class="float-right">
                                                    <a href="define/categorie/<?= $undefinedCategorie['id'] ?>"
                                                       class="icon-add"><i class="fas fa-plus-circle"></i></a>
                                                    <a href="delete/categorie/<?= $undefinedCategorie['id'] ?>"
                                                       class="icon-delete"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Section container, elle contient tous les articles et les catégories-->
            <div class="col-8">
                <div class="p-5" id="container">

                    <div class="card-body">
                        <ol class="list-unstyled">
                            <?php
                            /** @var wikiArticlesModel[] $getAllCategories */
                            foreach ($getAllCategories as $category):?>
                                <div class="categorie-container mt-4" id="categorie-<?= $category['id'] ?>">
                                    <span class="ml-2"><?= $category['name'] ?></span>
                                    <a href="delete/categorie/<?= $category['id'] ?>"
                                       class="float-right wiki-icons mr-2"><i class="fas fa-trash-alt"></i></a>
                                    <a href="edit/categorie/<?= $category['id'] ?>" class="float-right wiki-icons mr-3"><i
                                                class="fas fa-edit"></i></a>
                                </div>
                                <?php $getArticles = $articles->getAllArticlesInCategory($category['id']);
                                foreach ($getArticles as $article):?>
                                    <div class="ml-5 mt-1 article-container" id="article-<?= $article['id'] ?>">
                                        <span class="ml-2"><?= $article['title'] ?></span>
                                        <a href="delete/article/<?= $article['id'] ?>"
                                           class="float-right wiki-icons mr-2"><i class="fas fa-trash-alt"></i></a>
                                        <a href="edit/article/<?= $article['id'] ?>"
                                           class="float-right wiki-icons mr-3"><i class="fas fa-edit"></i></a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>

                        </ol>
                    </div>

                </div>
            </div>

        </div>

    </div>


<?php $content = ob_get_clean(); ?>

<?php require(getenv("PATH_ADMIN_VIEW") . 'template.php'); ?>