<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\Utils;

$title = LangManager::translate("wiki.title.edit_category");
$description = LangManager::translate("wiki.title.dashboard_desc");

/** @var \CMW\Entity\Wiki\WikiCategoriesEntity $categorie */
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="" method="post">
                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title"><?= LangManager::translate("wiki.title.edit_category") ?> :</h3>
                        </div>

                        <div class="card-body">

                            <label for="name"><?= LangManager::translate("wiki.add.category_name") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="name" value="<?= $categorie->getName() ?>" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_name_placeholder") ?>" required>

                            </div>

                            <label for="description"><?= LangManager::translate("wiki.add.category_description") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-paragraph"></i></span>
                                </div>
                                <input type="text" name="description" value="<?= $categorie->getDescription() ?>"
                                       class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_description_placeholderr") ?>"
                                       required>
                            </div>

                            <label for="icon"><?= LangManager::translate("wiki.add.category_icon") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                </div>
                                <input type="text" name="icon" value="<?= $categorie->getIcon() ?>" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_icon_placeholder") ?>" required>
                            </div>
                            <small class="form-text"> <?= LangManager::translate("wiki.add.hint_icon") ?> <a
                                        href="https://fontawesome.com" target="_blank">FontAwesome.com</a></small>

                            <label class="mt-4" for="slug"><?= LangManager::translate("wiki.add.category_slug") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= Utils::getHttpProtocol() . '://' . $_SERVER['SERVER_NAME'] . getenv("PATH_SUBFOLDER") . 'wiki/' ?></span>
                                </div>
                                <input type="text" name="slug" value="<?= $categorie->getSlug() ?>" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_slug_placeholder") ?>" required>
                            </div>

                        </div>


                        <div class="card-footer">

                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success float-left">
                                <input type="checkbox" name="isDefine" value="1" class="custom-control-input"
                                       id="customSwitch3" <?= ($categorie->getIsDefine() ? "checked" : "") ?>>
                                <label class="custom-control-label"
                                       for="customSwitch3"><?= LangManager::translate("wiki.edit.category_enable") ?></label>
                            </div>

                            <button type="submit"
                                    class="btn btn-primary float-right"><?= LangManager::translate("core.btn.save") ?></button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

