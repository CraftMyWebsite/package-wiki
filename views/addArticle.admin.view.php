<?php
use CMW\Manager\Security\SecurityManager;
use CMW\Manager\Lang\LangManager;
use CMW\Model\Wiki\WikiArticlesModel;
use CMW\Model\Wiki\WikiCategoriesModel;
use CMW\Utils\Utils;
$title = LangManager::translate("wiki.title.dashboard_title");
$description = LangManager::translate("wiki.title.dashboard_desc");

?>
<div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("wiki.title.add_article") ?></h4>
            </div>
            <div class="card-body">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 ">
                                <form method="post">
                                    <?php (new SecurityManager())->insertHiddenToken() ?>
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