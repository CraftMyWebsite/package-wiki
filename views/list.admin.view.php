<?php
use CMW\Utils\SecurityService;
use CMW\Manager\Lang\LangManager;
use CMW\Model\Wiki\WikiArticlesModel;
use CMW\Model\Wiki\WikiCategoriesModel;
use CMW\Utils\Utils;
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
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("wiki.title.add_category") ?></h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php (new SecurityService())->insertHiddenToken() ?>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <h6><?= LangManager::translate("wiki.add.category_name") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" name="name" required
                                       placeholder="<?= LangManager::translate("wiki.add.category_name_placeholder") ?>">
                                <div class="form-control-icon">
                                    <i class="fas fa-heading"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <h6><?= LangManager::translate("wiki.add.category_description") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" name="description" required
                                       placeholder="<?= LangManager::translate("wiki.add.category_description_placeholder") ?>">
                                <div class="form-control-icon">
                                    <i class="fas fa-paragraph"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <h6><?= LangManager::translate("wiki.add.category_icon") ?> :</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" name="icon" required
                                       placeholder="<?= LangManager::translate("wiki.add.category_icon_placeholder") ?>">
                                <div class="form-control-icon">
                                    <i class="fas fa-icons"></i>
                                </div>
                                <small class="form-text"><?= LangManager::translate("wiki.add.hint_icon") ?> <a
                                        href="https://fontawesome.com/search?o=r&m=free" target="_blank">FontAwesome.com</a></small>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <h6><?= LangManager::translate("wiki.add.category_slug") ?> :</h6>
                            <div class="input-group mb-3">
                                <span class="input-group-text" ><?= Utils::getHttpProtocol() . '://' . $_SERVER['SERVER_NAME'] . getenv("PATH_SUBFOLDER") . "wiki/" ?></span>
                                <input type="text" name="slug" required class="form-control" placeholder="<?= LangManager::translate("wiki.add.category_slug_placeholder") ?>">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"><?= LangManager::translate("core.btn.add") ?></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("wiki.title.add_article") ?> (Ne fonctionne pas encore)</h4>
                    <a href="article/add" class="btn btn-success">En attendant</a>
            </div>
            <div class="card-body">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" action="">
                                    <?php (new SecurityService())->insertHiddenToken() ?>
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <h6><?= LangManager::translate("wiki.add.article_title") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="title" required
                                                       placeholder="<?= LangManager::translate("wiki.add.article_title_placeholder") ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-heading"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <h6><?= LangManager::translate("wiki.add.category_icon") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="icon" required
                                                       placeholder="<?= LangManager::translate("wiki.add.category_icon_placeholder") ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-icons"></i>
                                                </div>
                                                <small class="form-text"><?= LangManager::translate("wiki.add.hint_icon") ?> <a
                                                        href="https://fontawesome.com/search?o=r&m=free" target="_blank">FontAwesome.com</a></small>
                                            </div>
                                        </div>
                                    </div>
                                        <h6><?= LangManager::translate("wiki.add.article_categorie") ?> :</h6>
                                            <select class="choices form-select" name="categorie" required>
                                                <?php foreach ($categories as $category): ?>        
                                                    <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <h6><?= LangManager::translate("wiki.add.article_content") ?> :</h6>
                                        <textarea name="content" id="summernote-1" required></textarea>
                                    <div class="text-center mt-2">
                                        <button type="submit" class="btn btn-primary"><?= LangManager::translate("core.btn.add") ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("wiki.title.manage") ?></h4>
            </div>
            <div class="card-body">
                <div class="divider">
                    <div class="divider-text"><?= LangManager::translate("wiki.title.actif") ?></div>
                </div>
                <?php foreach ($categories as $category):?>
                    <div class="card-in-card table-responsive mb-4">
                        <table class="table-borderless table table-hover mt-1">
                            <thead>
                                <tr>
                                    <th id="categorie-<?= $category->getId() ?>"><i class="<?= $category->getIcon() ?>"></i> <?= $category->getName() ?></th>
                                    <th class="text-end">
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
                                                <?= LangManager::translate("wiki.modal.deletealert") ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                                </button>
                                                <a href="categorie/delete/<?= $category->getId() ?>" class="btn btn-danger ml-1">
                                                    <i class="bx bx-check d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
                                                </a>                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </thead>
                            <tbody>
                                <?php foreach ($category->getArticles() as $article):?>
                                <tr id="article-<?= $article->getId() ?>">
                                    <td class="ps-4 text-bold-500"><i class="<?= $article->getIcon() ?>"></i> <?= $article->getTitle() ?></td>
                                    <td class="text-end">
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
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                                </button>
                                                <a href="article/delete/<?= $article->getId() ?>" class="btn btn-danger ml-1">
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
                <?php endforeach; ?>

                <div class="divider">
                    <div class="divider-text"><?= LangManager::translate("wiki.title.inactif") ?></div>
                </div>
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