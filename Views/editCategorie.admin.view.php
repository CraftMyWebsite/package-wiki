<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

$title = LangManager::translate("wiki.title.edit_category");
$description = LangManager::translate("wiki.title.dashboard_desc");

/** @var \CMW\Entity\Wiki\WikiCategoriesEntity $categorie */
?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-gears"></i> <span class="m-lg-auto"><?= LangManager::translate("wiki.title.edit_category") ?></span></h3>
    <div class="buttons">
        <button form="edit" type="submit"
                class="btn btn-primary"><?= LangManager::translate("core.btn.save", lineBreak: true) ?></button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="post" action="" id="edit">
            <?php (new SecurityManager())->insertHiddenToken() ?>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" value="1" id="customSwitch3" <?= ($categorie->getIsDefine() ? "checked" : "") ?> name="isDefine" checked>
                <label class="form-check-label" for="likes"><h6><?= LangManager::translate("wiki.edit.category_enable") ?></h6></label>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h6><?= LangManager::translate("wiki.add.category_name") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" name="name" required value="<?= $categorie->getName() ?>"
                               placeholder="<?= LangManager::translate("wiki.add.category_name_placeholder") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-heading"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <h6><?= LangManager::translate("wiki.add.category_description") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" name="description" required value="<?= $categorie->getDescription() ?>"
                               placeholder="<?= LangManager::translate("wiki.add.category_description_placeholder") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-paragraph"></i>
                </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <h6><?= LangManager::translate("wiki.add.category_icon") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" name="icon" required value="<?= $categorie->getIcon() ?>"
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
                        <span class="input-group-text" ><?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . "wiki/" ?></span>
                        <input type="text" value="<?= $categorie->getSlug() ?>" name="slug" required class="form-control" placeholder="<?= LangManager::translate("wiki.add.category_slug_placeholder") ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>