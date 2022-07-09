<?php
$title = WIKI_DASHBOARD_TITLE_ADD_CATEGORY;
$description = WIKI_DASHBOARD_DESC;

?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="" method="post">
                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title"><?= WIKI_DASHBOARD_TITLE_ADD_CATEGORY ?> :</h3>
                        </div>

                        <div class="card-body">

                            <label for="name"><?= WIKI_DASHBOARD_ADD_CATEGORY_NAME ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="name" class="form-control"
                                       placeholder="<?= WIKI_DASHBOARD_ADD_CATEGORY_NAME_PLACEHOLDER ?>" required>

                            </div>

                            <label for="description"><?= WIKI_DASHBOARD_ADD_CATEGORY_DESCRIPTION ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-paragraph"></i></span>
                                </div>
                                <input type="text" name="description" class="form-control"
                                       placeholder="<?= WIKI_DASHBOARD_ADD_CATEGORY_DESCRIPTION_PLACEHOLDER ?>"
                                       required>
                            </div>

                            <label for="icon"><?= WIKI_DASHBOARD_ADD_CATEGORY_ICON ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                </div>
                                <input type="text" name="icon" class="form-control"
                                       placeholder="<?= WIKI_DASHBOARD_ADD_CATEGORY_ICON_PLACEHOLDER ?>" required>
                            </div>
                            <small class="form-text"><?= WIKI_DASHBOARD_ADD_HINT_ICON ?> <a
                                        href="https://fontawesome.com" target="_blank">FontAwesome.com</a></small>

                            <label class="mt-4" for="slug"><?= WIKI_DASHBOARD_ADD_CATEGORY_SLUG ?></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= "https://" . $_SERVER['SERVER_NAME'] . getenv("PATH_SUBFOLDER") . 'wiki/' ?></span>
                                </div>
                                <input type="text" name="slug" class="form-control"
                                       placeholder="<?= WIKI_DASHBOARD_ADD_CATEGORY_SLUG_PLACEHOLDER ?>" required>
                            </div>

                        </div>


                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-primary float-right"><?= CORE_BTN_SAVE ?></button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>