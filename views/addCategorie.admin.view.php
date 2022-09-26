<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\Utils;

$title = LangManager::translate("wiki.title.add_category");
$description = LangManager::translate("wiki.title_dashboard_desc");

?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="" method="post">
                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title"><?= LangManager::translate("wiki.title.add_category") ?> :</h3>
                        </div>

                        <div class="card-body">

                            <label for="name"><?= LangManager::translate("wiki.add.category_name") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="name" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_name_placeholder") ?>" required>

                            </div>

                            <label for="description"><?= LangManager::translate("wiki.add.category_description") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-paragraph"></i></span>
                                </div>
                                <input type="text" name="description" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_description_placeholder") ?>"
                                       required>
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

                            <label class="mt-4" for="slug"><?= LangManager::translate("wiki.add.category_slug") ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= Utils::getHttpProtocol() . '://'  . $_SERVER['SERVER_NAME'] . getenv("PATH_SUBFOLDER") . 'wiki/' ?></span>
                                </div>
                                <input type="text" name="slug" class="form-control"
                                       placeholder="<?= LangManager::translate("wiki.add.category_slug_placeholder") ?>" required>
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