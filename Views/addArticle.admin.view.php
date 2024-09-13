<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate('wiki.title.dashboard_title');
$description = LangManager::translate('wiki.title.dashboard_desc');

?>
<h3><?= LangManager::translate('wiki.title.add_article') ?></h3>

<form action="" method="post" class="card">
    <?php (new SecurityManager())->insertHiddenToken() ?>
    <div class="grid-2">
        <div>
            <label for="title"><?= LangManager::translate('wiki.add.article_title') ?> :</label>
            <div class="input-group">
                <i class="fa-solid fa-heading"></i>
                <input type="text" id="title" name="title" required
                       placeholder="<?= LangManager::translate('wiki.add.article_title_placeholder') ?>">
            </div>
        </div>
        <div>
            <div class="icon-picker" data-id="icon" data-name="icon"
                 data-label="<?= LangManager::translate('wiki.add.category_icon') ?> :"
                 data-placeholder="Sélectionner un icon" data-value=""></div>
        </div>
    </div>
    <label for="content"><?= LangManager::translate('wiki.add.article_content') ?> :</label>
    <textarea id="content" class="tinymce" name="content" data-tiny-height="500"></textarea>
    <div class="mt-4">
        <button id="saveButton" type="submit"
                class="btn-center btn-primary"><?= LangManager::translate('core.btn.save') ?></button>
    </div>
</form>
