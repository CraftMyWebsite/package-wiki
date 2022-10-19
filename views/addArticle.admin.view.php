<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

$title = LangManager::translate("wiki.title.add_article");
$description = LangManager::translate("wiki.title.dashboard_desc");

/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $categories */
?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="" method="post">
                    <?php (new SecurityService())->insertHiddenToken() ?>
                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title"><?= LangManager::translate("wiki.title.add_article") ?> :</h3>
                        </div>

                        <div class="card-body">

                            <label for="title"><?= LangManager::translate("wiki.add.article_title") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="title" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.article_title_placeholder") ?>" required>

                            </div>

                            <label for="categorie"><?= LangManager::translate("wiki.add.article_categorie") ?></label>
                            <div class="input-group mb-3">

                                <select class="form-control" name="categorie" required>
                                    <?php

                                    foreach ($categories as $category): ?>
                                        <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                                    <?php endforeach; ?>
                                </select>


                            </div>

                            <label for="icon"><?= LangManager::translate("wiki.add.category_icon") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                </div>
                                <input type="text" name="icon" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_icon_placeholder") ?>" required>
                            </div>
                            <small class="form-text"><?= LangManager::translate("wiki.add.hint_icon") ?> <a
                                        href="https://fontawesome.com" target="_blank">FontAwesome.com</a></small>


                            <label for="content" class="mt-3"><?= LangManager::translate("wiki.add.article_content") ?></label>
                            <div class="input-group mb-3">
                                <textarea id="summernote" name="content" class="form-control"
                                          placeholder="<?= LangManager::translate("wiki.add.article_content_placeholder") ?>"
                                          required> </textarea>

                            </div>

                        </div>


                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-primary float-right"><?= LangManager::translate("core.btn.save") ?></button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
