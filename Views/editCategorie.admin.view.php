<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

$title = LangManager::translate("wiki.title.edit_category");
$description = LangManager::translate("wiki.title.dashboard_desc");

/** @var \CMW\Entity\Wiki\WikiCategoriesEntity $categorie */
?>

<h3><i class="fa-solid fa-gears"></i> <?= LangManager::translate("wiki.title.edit_category") ?></h3>

<form method="post" action="" class="card">
    <?php (new SecurityManager())->insertHiddenToken() ?>
    <div>
        <label class="toggle">
            <p class="toggle-label"><?= LangManager::translate("wiki.edit.category_enable") ?></p>
            <input type="checkbox" class="toggle-input" <?= ($categorie->getIsDefine() ? "checked" : "") ?> name="isDefine">
            <div class="toggle-slider"></div>
        </label>
    </div>

        <label for="name"><?= LangManager::translate("wiki.add.category_name") ?> :</label>
        <div class="input-group">
            <i class="fa-solid fa-heading"></i>
            <input type="text" id="name" name="name" required value="<?= $categorie->getName() ?>" placeholder="<?= LangManager::translate("wiki.add.category_name_placeholder") ?>">
        </div>
        <label for="description"><?= LangManager::translate("wiki.add.category_description") ?> :</label>
        <div class="input-group">
            <i class="fa-solid fa-paragraph"></i>
            <input type="text" id="description" name="description" value="<?= $categorie->getDescription() ?>" required placeholder="<?= LangManager::translate("wiki.add.category_description_placeholder") ?>">
        </div>
        <div class="icon-picker" data-id="icon" data-name="icon" data-label="<?= LangManager::translate("wiki.add.category_icon") ?> :" data-placeholder="Sélectionner un icon" data-value="<?= $categorie->getIcon() ?>"></div>
        <label for="slug"><?= LangManager::translate("wiki.add.category_slug") ?> :</label>
        <div class="input-group">
            <i><?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . "wiki/" ?></i>
            <input type="text" id="slug" name="slug" value="<?= $categorie->getSlug() ?>" placeholder="<?= LangManager::translate("wiki.add.category_slug_placeholder") ?>">
        </div>
        <button type="submit" class="btn-primary btn-center">
            <?= LangManager::translate("core.btn.save") ?>
        </button>
</form>