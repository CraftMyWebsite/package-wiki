<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate("wiki.title.edit_article");
$description = LangManager::translate("wiki.title.dashboard_desc");

/** @var \CMW\Entity\Wiki\WikiArticlesEntity $article */
/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $categories */
?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-gears"></i> <span class="m-lg-auto"><?= LangManager::translate("wiki.title.edit_article") ?></span></h3>
    <div class="buttons">
        <button form="edit" type="submit"
                class="btn btn-primary"><?= LangManager::translate("core.btn.save", lineBreak: true) ?></button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <form method="post" action="" id="edit">
                            <?php (new SecurityManager())->insertHiddenToken() ?>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" id="customSwitch3" name="isDefine" checked <?= ($article->getIsDefine() ? "checked" : "") ?>>
                                <label class="form-check-label" for="customSwitch3"><h6><?= LangManager::translate("wiki.edit.article_enable") ?></h6></label>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <h6><?= LangManager::translate("wiki.add.article_title") ?> :</h6>
                                    <div class="form-group position-relative has-icon-left">
                                        <input type="text" class="form-control" name="title" required value="<?= $article->getTitle() ?>"
                                               placeholder="<?= LangManager::translate("wiki.add.article_title_placeholder") ?>">
                                        <div class="form-control-icon">
                                            <i class="fas fa-heading"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <h6><?= LangManager::translate("wiki.add.category_icon") ?> :</h6>
                                    <div class="form-group position-relative has-icon-left">
                                        <input type="text" class="form-control" name="icon" required value="<?= $article->getIcon() ?>"
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
                                    <option value="<?= $category->getId() ?>"
                                        <?= ($article->getCategoryId() === $category->getId() ? "selected" : "") ?> >
                                        <?= $category->getName() ?>
                                    </option>
                                    <?php endforeach; ?>
                                    </select>
                                <h6><?= LangManager::translate("wiki.add.article_content") ?> :</h6>
                                <textarea name="content" id="summernote-1" required><?= $article->getContent() ?></textarea>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>