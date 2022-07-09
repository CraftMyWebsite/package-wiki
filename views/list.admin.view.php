<?php

use CMW\Model\Wiki\WikiArticlesModel;
use CMW\Model\Wiki\WikiCategoriesModel;

$title = WIKI_DASHBOARD_TITLE;
$description = WIKI_DASHBOARD_DESC;

/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $categories */
/** @var \CMW\Entity\Wiki\WikiArticlesEntity[] $undefinedArticles */
/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $undefinedCategories */

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
                                        <span>(<strong><?= (new WikiArticlesModel())->getNumberOfUndefinedArticles() ?></strong>)</span>
                                    </div>
                                </div>
                                <div class="card-body" id="list-articles">
                                    <ul class="list-unstyled">
                                        <?php
                                        foreach ($undefinedArticles as $undefinedArticle):?>
                                            <li><?= $undefinedArticle->getTitle() ?>
                                                <div class="float-right">
                                                    <a href="define/article/<?= $undefinedArticle->getId() ?>"
                                                       class="icon-add"><i class="fas fa-plus-circle"></i></a>
                                                    <a href="delete/article/<?= $undefinedArticle->getId() ?>"
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
                                        <span>(<strong><?= (new WikiCategoriesModel())->getNumberOfUndefinedCategories() ?></strong>)</span>
                                    </div>
                                </div>
                                <div class="card-body" id="list-articles">
                                    <ul class="list-unstyled">
                                        <?php
                                        foreach ($undefinedCategories as $undefinedCategorie):?>
                                            <li><?= $undefinedCategorie->getName() ?>
                                                <div class="float-right">
                                                    <a href="define/categorie/<?= $undefinedCategorie->getId() ?>"
                                                       class="icon-add"><i class="fas fa-plus-circle"></i></a>
                                                    <a href="delete/categorie/<?= $undefinedCategorie->getId() ?>"
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


            <!-- Section container, elle contient tous les articles et les catégories définis -->
            <div class="col-8">
                <div class="p-5" id="container">

                    <div class="card-body">
                        <ol class="list-unstyled">
                            <?php
                            foreach ($categories as $category):?>
                                <div class="categorie-container mt-4" id="categorie-<?= $category->getId() ?>">
                                    <span class="ml-2"><?= $category->getName() ?></span>
                                    <a href="delete/categorie/<?= $category->getId() ?>"
                                       class="float-right wiki-icons mr-2"><i class="fas fa-trash-alt"></i></a>
                                    <a href="edit/categorie/<?= $category->getId() ?>"
                                       class="float-right wiki-icons mr-3"><i
                                                class="fas fa-edit"></i></a>
                                </div>
                                <?php
                                foreach ($category->getArticles() as $article):?>
                                    <div class="ml-5 mt-1 article-container" id="article-<?= $article->getId() ?>">
                                        <span class="ml-2"><?= $article->getTitle() ?></span>
                                        <a href="delete/article/<?= $article->getId() ?>"
                                           class="float-right wiki-icons mr-2"><i class="fas fa-trash-alt"></i></a>
                                        <a href="edit/article/<?= $article->getId() ?>"
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