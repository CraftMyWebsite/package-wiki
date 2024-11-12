<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Model\Wiki\WikiArticlesModel;
use CMW\Model\Wiki\WikiCategoriesModel;
use CMW\Utils\Website;

$title = LangManager::translate('wiki.title.dashboard_title');
$description = LangManager::translate('wiki.title.dashboard_desc');

/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $categories */
/** @var \CMW\Entity\Wiki\WikiArticlesEntity[] $undefinedArticles */
/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $undefinedCategories */
?>

<h3><i class="fa-solid fa-book"></i> <?= LangManager::translate('wiki.title.dashboard_title') ?></h3>

<div class="grid-2">
    <div class="card">
        <h6><?= LangManager::translate('wiki.title.manage') ?> <?= LangManager::translate('wiki.title.actif') ?></h6>
        <?php if ($categories): ?>
            <?php foreach ($categories as $category): ?>
                <div class="flex justify-between">
                    <div id="category-<?= $category->getId() ?>"><i
                            class="<?= $category->getIcon() ?>"></i> <?= $category->getName() ?> -
                        <i><small><?= mb_strimwidth($category->getDescription(), 0, 45, '...') ?></small></i></div>
                    <div class="space-x-2">
                        <a href="wiki/article/add/<?= $category->getId() ?>"><i
                                class="text-success fa-solid fa-circle-plus"></i></a>
                        <a href="wiki/category/edit/<?= $category->getId() ?>"><i
                                class="text-info fas fa-edit"></i></a>
                        <button data-modal-toggle="modal-delete-<?= $category->getId() ?>" type="button"><i
                                class="text-danger fas fa-trash-alt"></i></button>
                    </div>
                </div>
                <div id="modal-delete-<?= $category->getId() ?>" class="modal-container">
                    <div class="modal">
                        <div class="modal-header-danger">
                            <h6><?= LangManager::translate('wiki.modal.delete') ?> <?= $category->getName() ?></h6>
                            <button type="button" data-modal-hide="modal-delete-<?= $category->getId() ?>"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="modal-body">
                            <?= LangManager::translate('wiki.modal.deletecatalert') ?>
                        </div>
                        <div class="modal-footer">
                            <a href="wiki/categorie/delete/<?= $category->getId() ?>" class="btn-danger">
                                <?= LangManager::translate('core.btn.delete') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php foreach ($category->getArticles() as $article): ?>
                    <div class="flex justify-between pl-3" id="article-<?= $article->getId() ?>">
                        <div class="ps-4 text-bold-500"><i
                                class="<?= $article->getIcon() ?>"></i> <?= $article->getTitle() ?>
                        </div>
                        <div class="space-x-2">
                                        <span class="me-3">
                                            <a href="wiki/article/positionDown/<?= $article->getId() ?>/<?= $article->getPosition() ?>"><i
                                                    class="fa-xs fa-solid fa-circle-minus"></i></a>
                                            <b><?= $article->getPosition() ?></b>
                                            <a href="wiki/article/positionUp/<?= $article->getId() ?>/<?= $article->getPosition() ?>"><i
                                                    class="fa-xs fa-solid fa-circle-plus"></i></a>
                                        </span>
                            <a target="_blank"
                               href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'wiki/' . $category->getSlug() . '/' . $article->getSlug() ?>"><i
                                    class="fa-solid fa-up-right-from-square"></i></a>
                            <a href="wiki/article/edit/<?= $article->getId() ?>"><i
                                    class="text-info fas fa-edit"></i></a>
                            <button data-modal-toggle="modal-deletee-<?= $article->getId() ?>" type="button"><i
                                    class="text-danger fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                    <div id="modal-deletee-<?= $article->getId() ?>" class="modal-container">
                        <div class="modal">
                            <div class="modal-header-danger">
                                <h6><?= LangManager::translate('wiki.modal.delete') ?> <?= $article->getTitle() ?></h6>
                                <button type="button" data-modal-hide="modal-deletee-<?= $article->getId() ?>"><i
                                        class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <?= LangManager::translate('wiki.modal.deletealert') ?>
                            </div>
                            <div class="modal-footer">
                                <a href="wiki/article/delete/<?= $article->getId() ?>" class="btn btn-danger">
                                    <?= LangManager::translate('core.btn.delete') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <?= LangManager::translate('wiki.alert.create_before') ?>
            </div>
        <?php endif ?>
        <div class="mt-4">
            <button data-modal-toggle="modal-add-cat" class="btn-primary btn-center" type="button"><i
                    class="fa-solid fa-circle-plus"></i> <?= LangManager::translate('wiki.button.add_category') ?>
            </button>
        </div>

    </div>


    <div class="card">
        <h6><?= LangManager::translate('wiki.title.manage') ?> <?= LangManager::translate('wiki.title.inactif') ?></h6>
        <div><?= LangManager::translate('wiki.title.inactifcat') ?>
            (<strong><?= (new WikiCategoriesModel())->getNumberOfUndefinedCategories() ?></strong>)
        </div>
        <?php foreach ($undefinedCategories as $undefinedCategorie): ?>
            <div class="flex justify-between">
                <div class="pl-4"><i
                        class="<?= $undefinedCategorie->getIcon() ?>"></i> <?= $undefinedCategorie->getName() ?></div>
                <div class="space-x-2">
                    <a href="wiki/categorie/define/<?= $undefinedCategorie->getId() ?>"><i
                            class="text-success fa-solid fa-rocket"></i></a>
                    <a href="wiki/categorie/edit/<?= $undefinedCategorie->getId() ?>"><i
                            class="text-info fas fa-edit"></i></a>
                    <button data-modal-toggle="modal-deleteee-<?= $undefinedCategorie->getId() ?>" type="button"><i
                            class="text-danger fas fa-trash-alt"></i></button>
                </div>
            </div>

            <div id="modal-deleteee-<?= $undefinedCategorie->getId() ?>" class="modal-container">
                <div class="modal">
                    <div class="modal-header-danger">
                        <h6><?= LangManager::translate('wiki.modal.delete') ?> <?= $undefinedCategorie->getName() ?></h6>
                        <button type="button" data-modal-hide="modal-deleteee-<?= $undefinedCategorie->getId() ?>"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <?= LangManager::translate('wiki.modal.deletealert') ?>
                    </div>
                    <div class="modal-footer">
                        <a href="wiki/categorie/delete/<?= $undefinedCategorie->getId() ?>" class="btn-danger">
                            <?= LangManager::translate('core.btn.delete') ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?= LangManager::translate('wiki.title.inactifart') ?>
        (<strong><?= (new WikiArticlesModel())->getNumberOfUndefinedArticles() ?></strong>)
        <?php foreach ($undefinedArticles as $undefinedArticle): ?>
            <div class="flex justify-between">
                <div class="pl-4"><i
                        class="<?= $undefinedArticle->getIcon() ?>"></i> <?= $undefinedArticle->getTitle() ?></div>
                <div class="space-x-2">
                    <a href="wiki/article/define/<?= $undefinedArticle->getId() ?>"><i
                            class="text-success fa-solid fa-rocket"></i></a>
                    <a href="wiki/article/edit/<?= $undefinedArticle->getId() ?>"><i class="text-info fas fa-edit"></i></a>
                    <button data-modal-toggle="modal-delette-<?= $undefinedArticle->getId() ?>" type="button"><i
                            class="text-danger fas fa-trash-alt"></i></button>
                </div>
            </div>
            <div id="modal-delette-<?= $undefinedArticle->getId() ?>" class="modal-container">
                <div class="modal">
                    <div class="modal-header-danger">
                        <h6><?= LangManager::translate('wiki.modal.delete') ?> <?= $undefinedArticle->getTitle() ?></h6>
                        <button type="button" data-modal-hide="modal-delette-<?= $undefinedArticle->getId() ?>"><i
                                class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <?= LangManager::translate('wiki.modal.deletealert') ?>
                    </div>
                    <div class="modal-footer">
                        <a href="wiki/article/delete/<?= $undefinedArticle->getId() ?>" class="btn-danger">
                            <?= LangManager::translate('core.btn.delete') ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div id="modal-add-cat" class="modal-container">
    <div class="modal">
        <div class="modal-header">
            <h6>Titre de la modal</h6>
            <button type="button" data-modal-hide="modal-add-cat"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="post" action="">
            <?php SecurityManager::getInstance()->insertHiddenToken() ?>
            <div class="modal-body">
                <label for="name"><?= LangManager::translate('wiki.add.category_name') ?> :</label>
                <div class="input-group">
                    <i class="fa-solid fa-heading"></i>
                    <input type="text" id="name" name="name" required
                           placeholder="<?= LangManager::translate('wiki.add.category_name_placeholder') ?>">
                </div>
                <label for="description"><?= LangManager::translate('wiki.add.category_description') ?> :</label>
                <div class="input-group">
                    <i class="fa-solid fa-paragraph"></i>
                    <input type="text" id="description" name="description" required
                           placeholder="<?= LangManager::translate('wiki.add.category_description_placeholder') ?>">
                </div>
                <div class="icon-picker" data-id="icon" data-name="icon"
                     data-label="<?= LangManager::translate('wiki.add.category_icon') ?> :"
                     data-placeholder="Sélectionner un icon" data-value=""></div>
                <label for="slug"><?= LangManager::translate('wiki.add.category_slug') ?> :</label>
                <div class="input-group">
                    <i><?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'wiki/' ?></i>
                    <input type="text" id="slug" name="slug"
                           placeholder="<?= LangManager::translate('wiki.add.category_slug_placeholder') ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-primary">
                    <?= LangManager::translate('core.btn.add') ?>
                </button>
            </div>
        </form>
    </div>
</div>
