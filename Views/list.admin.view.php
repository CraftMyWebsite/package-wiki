<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Manager\Lang\LangManager;
use CMW\Model\Wiki\WikiArticlesModel;
use CMW\Model\Wiki\WikiCategoriesModel;
use CMW\Utils\Utils;
use CMW\Utils\Website;

$title = LangManager::translate("wiki.title.dashboard_title");
$description = LangManager::translate("wiki.title.dashboard_desc");

/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $categories */
/** @var \CMW\Entity\Wiki\WikiArticlesEntity[] $undefinedArticles */
/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $undefinedCategories */
?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-book"></i> <span class="m-lg-auto"><?= LangManager::translate("wiki.title.dashboard_title") ?></span></h3>
</div>

<section class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("wiki.title.manage") ?> <?= LangManager::translate("wiki.title.actif") ?></h4>
            </div>
            <div class="card-body">
                <?php if ($categories): ?>
                <?php foreach ($categories as $category):?>
                    <div class="card-in-card table-responsive mb-4">
                        <table class="table-borderless table table-hover mt-1">
                            <thead>
                                <tr>
                                    <th id="categorie-<?= $category->getId() ?>"><i class="<?= $category->getIcon() ?>"></i> <?= $category->getName() ?> -
                                    <i><small><?= mb_strimwidth($category->getDescription(), 0, 45, '...') ?></small></i></th>
                                    <th class="text-end">
                                        <a href="article/add/<?= $category->getId() ?>"><i class="text-success me-3 fa-solid fa-circle-plus"></i></a>
                                        <a href="categorie/edit/<?= $category->getId() ?>"><i class="text-primary me-3 fas fa-edit"></i></a>
                                        <a type="button" data-bs-toggle="modal" data-bs-target="#delete-<?= $category->getId() ?>">
                                            <i class="text-danger fas fa-trash-alt"></i>
                                        </a>
                                    </th>
                                </tr>
                                <div class="modal fade text-left" id="delete-<?= $category->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("wiki.modal.delete") ?> <?= $category->getName() ?></h5>
                                            </div>
                                            <div class="modal-body">
                                                <?= LangManager::translate("wiki.modal.deletecatalert") ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x"></i>
                                                    <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                                </button>
                                                <a href="categorie/delete/<?= $category->getId() ?>" class="btn btn-danger ml-1">
                                                    <i class="bx bx-check"></i>
                                                    <span class=""><?= LangManager::translate("core.btn.delete") ?></span>
                                                </a>                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </thead>
                            <tbody>
                                <?php foreach ($category->getArticles() as $article):?>
                                <tr id="article-<?= $article->getId() ?>">
                                    <td class="ps-4 text-bold-500"><i class="<?= $article->getIcon() ?>"></i> <?= $article->getTitle() ?>
                                    </td>
                                    <td class="text-end">
                                        <span class="me-3">
                                            <a href="article/positionDown/<?= $article->getId() ?>/<?= $article->getPosition() ?>"><i class="fa-xs fa-solid fa-circle-minus"></i></a>
                                            <b><?= $article->getPosition() ?></b>
                                            <a href="article/positionUp/<?= $article->getId() ?>/<?= $article->getPosition() ?>"><i class="fa-xs fa-solid fa-circle-plus"></i></a>
                                        </span>
                                        <a target="_blank" href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . "wiki/" . $category->getSlug() ."/" . $article->getSlug() ?>"><i class="me-3 fa-solid fa-up-right-from-square"></i></a>
                                        <a href="article/edit/<?= $article->getId() ?>"><i class="text-primary me-3 fas fa-edit"></i></a>
                                        <a type="button" data-bs-toggle="modal" data-bs-target="#deletee-<?= $article->getId() ?>">
                                            <i class="text-danger fas fa-trash-alt"></i>
                                        </a>
                                    </td> 
                                </tr>
                                <div class="modal fade text-left" id="deletee-<?= $article->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("wiki.modal.delete") ?> <?= $article->getTitle() ?></h5>
                                            </div>
                                            <div class="modal-body">
                                                <?= LangManager::translate("wiki.modal.deletealert") ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x"></i>
                                                    <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                                </button>
                                                <a href="article/delete/<?= $article->getId() ?>" class="btn btn-danger ml-1">
                                                    <i class="bx bx-check"></i>
                                                    <span class=""><?= LangManager::translate("core.btn.delete") ?></span>
                                                </a>                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="alert alert-info">
                    <?= LangManager::translate("wiki.alert.create_before") ?>
            </div>
            <?php endif ?>
                <div class="divider">
                    <a type="button" data-bs-toggle="modal" data-bs-target="#add-cat">
                        <div class="divider-text"><i class="fa-solid fa-circle-plus"></i> <?= LangManager::translate("wiki.button.add_category") ?></div>
                    </a>
                </div>
            </div>
        </div>
    </div>




<div class="modal fade " id="add-cat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("wiki.title.add_category") ?></h5>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <?php (new SecurityManager())->insertHiddenToken() ?>            
                        <h6><?= LangManager::translate("wiki.add.category_name") ?> :</h6>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control" name="name" required placeholder="<?= LangManager::translate("wiki.add.category_name_placeholder") ?>">
                            <div class="form-control-icon">
                                <i class="fas fa-heading"></i>
                            </div>
                        </div>
                        <h6><?= LangManager::translate("wiki.add.category_description") ?> :</h6>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control" name="description" required placeholder="<?= LangManager::translate("wiki.add.category_description_placeholder") ?>">
                            <div class="form-control-icon">
                                <i class="fas fa-paragraph"></i>
                            </div>
                        </div>
                        <h6><?= LangManager::translate("wiki.add.category_icon") ?> :</h6>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control" name="icon" placeholder="<?= LangManager::translate("wiki.add.category_icon_placeholder") ?>">
                            <div class="form-control-icon">
                                <i class="fas fa-icons"></i>
                            </div>
                            <small class="form-text"><?= LangManager::translate("wiki.add.hint_icon") ?> <a href="https://fontawesome.com/search?o=r&m=free" target="_blank">FontAwesome.com</a></small>
                        </div>
                         <h6><?= LangManager::translate("wiki.add.category_slug") ?> :</h6>
                         <div class="input-group mb-3">
                             <span class="input-group-text" ><?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . "wiki/" ?></span>
                             <input type="text" name="slug" required class="form-control" placeholder="<?= LangManager::translate("wiki.add.category_slug_placeholder") ?>">
                         </div>                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i>
                    <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                </button>
                <button type="submit" class="btn btn-primary ml-1">
                    <i class="bx bx-check"></i>
                    <span class=""><?= LangManager::translate("core.btn.add") ?></span>
                </button>    
                </form>                            
            </div>
        </div>
    </div>
</div>




    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("wiki.title.manage") ?> <?= LangManager::translate("wiki.title.inactif") ?></h4>
            </div>
            <div class="card-body">
                <div class="card-in-card table-responsive mb-4">
                    <table class="table table-hover table-borderless mt-1 mb-0">
                        <thead>
                            <tr>
                                <th><?= LangManager::translate("wiki.title.inactifcat") ?> (<strong><?= (new WikiCategoriesModel())->getNumberOfUndefinedCategories() ?></strong>)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($undefinedCategories as $undefinedCategorie):?>
                            <tr>

                                <td class="ps-4 text-bold-500"><i class="<?= $undefinedCategorie->getIcon() ?>"></i> <?= $undefinedCategorie->getName() ?></td>
                                <td class="text-end">
                                    <a href="categorie/define/<?= $undefinedCategorie->getId() ?>"><i class="text-success me-3 fa-solid fa-rocket"></i></a>
                                    <a href="categorie/edit/<?= $undefinedCategorie->getId() ?>"><i class="text-primary me-3 fas fa-edit"></i></a>
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#deleteee-<?= $undefinedCategorie->getId() ?>">
                                        <i class="text-danger fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <div class="modal fade text-left" id="deleteee-<?= $undefinedCategorie->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("wiki.modal.delete") ?> <?= $undefinedCategorie->getName() ?></h5>
                                        </div>
                                        <div class="modal-body">
                                            <?= LangManager::translate("wiki.modal.deletealert") ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                            </button>
                                            <a href="categorie/delete/<?= $undefinedCategorie->getId() ?>" class="btn btn-danger ml-1">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
                                            </a>                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                                        
                <div class="card-in-card table-responsive">
                    <table class="table table-hover table-borderless mt-1 mb-0">
                        <thead>
                            <tr>
                                <th><?= LangManager::translate("wiki.title.inactifart") ?> (<strong><?= (new WikiArticlesModel())->getNumberOfUndefinedArticles() ?></strong>)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($undefinedArticles as $undefinedArticle):?>
                            <tr>
                                <td class="ps-4 text-bold-500"><i class="<?= $undefinedArticle->getIcon() ?>"></i> <?= $undefinedArticle->getTitle() ?></td>
                                <td class="text-end">
                                    <a href="article/define/<?= $undefinedArticle->getId() ?>"><i class="text-success me-3 fa-solid fa-rocket"></i></a>
                                    <a href="article/edit/<?= $undefinedArticle->getId() ?>"><i class="text-primary me-3 fas fa-edit"></i></a>
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#delette-<?= $undefinedArticle->getId() ?>">
                                        <i class="text-danger fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <div class="modal fade text-left" id="delette-<?= $undefinedArticle->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("wiki.modal.delete") ?> <?= $undefinedArticle->getTitle() ?></h5>
                                        </div>
                                        <div class="modal-body">
                                            <?= LangManager::translate("wiki.modal.deletealert") ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                            </button>
                                            <a href="article/delete/<?= $undefinedArticle->getId() ?>" class="btn btn-danger ml-1">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
                                            </a>                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>