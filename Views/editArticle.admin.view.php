<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

$title = LangManager::translate('wiki.title.edit_article');
$description = LangManager::translate('wiki.title.dashboard_desc');

/** @var \CMW\Entity\Wiki\WikiArticlesEntity $article */
/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $categories */
?>

<h3><?= LangManager::translate('wiki.title.edit_article') ?></h3>

<form action="" method="post" class="card">
    <?php (new SecurityManager())->insertHiddenToken() ?>
    <div class="grid-2">
        <div>
            <label for="title"><?= LangManager::translate('wiki.add.article_title') ?> :</label>
            <div class="input-group">
                <i class="fa-solid fa-heading"></i>
                <input type="text" id="title" name="title" required value="<?= $article->getTitle() ?>"
                       placeholder="<?= LangManager::translate('wiki.add.article_title_placeholder') ?>">
            </div>
        </div>
        <div>
            <div class="icon-picker" data-id="icon" data-name="icon" data-label="<?= LangManager::translate('wiki.add.category_icon') ?> :" data-placeholder="Sélectionner un icon" data-value="<?= $article->getIcon() ?>"></div>
        </div>
    </div>
    <div class="grid-2">
        <div>
            <label for="categorie"><?= LangManager::translate('wiki.add.article_categorie') ?> :</label>
            <select id="categorie" name="categorie" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>"
                        <?= ($article->getCategoryId() === $category->getId() ? 'selected' : '') ?> >
                        <?= $category->getName() ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="toggle">
                <p class="toggle-label"><?= LangManager::translate('wiki.edit.article_enable') ?></p>
                <input type="checkbox" id="isDefine" name="isDefine" class="toggle-input" <?= ($article->getIsDefine() ? 'checked' : '') ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
    </div>
    <label for="content"><?= LangManager::translate('wiki.add.article_content') ?> :</label>
    <textarea id="content" class="tinymce" name="content" data-tiny-height="500"><?= $article->getContentNotTranslate() ?></textarea>
    <div class="mt-4">
        <button id="saveButton" type="submit" class="btn-center btn-primary"><?= LangManager::translate('core.btn.save') ?></button>
    </div>
</form>