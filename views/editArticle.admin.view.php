<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Utils;
$title = LangManager::translate("wiki.title.edit_article");
$description = LangManager::translate("wiki.title.dashboard_desc");

/** @var \CMW\Entity\Wiki\WikiArticlesEntity $article */
/** @var \CMW\Entity\Wiki\WikiCategoriesEntity[] $categories */
?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-gears"></i> <span class="m-lg-auto"><?= LangManager::translate("wiki.title.edit_article") ?></span></h3>
</div>

<div class="card">
    <div class="card-body">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" id="isDefine" name="isDefine" checked <?= ($article->getIsDefine() ? "checked" : "") ?>>
                                <label class="form-check-label" for="isDefine"><h6><?= LangManager::translate("wiki.edit.article_enable") ?></h6></label>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <h6><?= LangManager::translate("wiki.add.article_title") ?> :</h6>
                                    <div class="form-group position-relative has-icon-left">
                                        <input type="text" class="form-control" id="title" name="title" required value="<?= $article->getTitle() ?>"
                                               placeholder="<?= LangManager::translate("wiki.add.article_title_placeholder") ?>">
                                        <div class="form-control-icon">
                                            <i class="fas fa-heading"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <h6><?= LangManager::translate("wiki.add.category_icon") ?> :</h6>
                                    <div class="form-group position-relative has-icon-left">
                                        <input type="text" class="form-control" id="icon" name="icon" required value="<?= $article->getIcon() ?>"
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
                                    <select class="choices form-select" id="categorie" name="categorie" required>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->getId() ?>"
                                        <?= ($article->getCategoryId() === $category->getId() ? "selected" : "") ?> >
                                        <?= $category->getName() ?>
                                    </option>
                                    <?php endforeach; ?>
                                    </select>
                                <h6><?= LangManager::translate("wiki.add.article_content") ?> :</h6>
                                <div class="card-in-card" id="editorjs"></div>
                                <div class="text-center mt-2">
                                        <button type="submit" id="saveButton" class="btn btn-primary"><?= LangManager::translate("core.btn.save") ?></button>
                                    </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    /**
     * Check inpt befor send
    */
     let input_title = document.querySelector("#title");
     let input_icon = document.querySelector("#icon");
     let button = document.querySelector("#saveButton");
     input_title.addEventListener("change", stateHandle);
     input_icon.addEventListener("change", stateHandle);
     function stateHandle() {
     if (document.querySelector("#title").value !="" && document.querySelector("#icon").value !="") {
      button.disabled = false;
      button.innerHTML = "<?= LangManager::translate("core.btn.add") ?>";
     }
     else {
      button.disabled = true;
      button.innerHTML = "<?= LangManager::translate('wiki.button.create_before') ?>";
     }
    }


    /**
     * EditorJS
     *  //TODO IMPLEMENT IMAGES
     */
    let editor = new EditorJS({
        placeholder: "<?= LangManager::translate('wiki.editor.start') ?>",
        logLevel: "ERROR",
        readOnly: false,
        holder: "editorjs",
        /**
         * Tools list
         */
        tools: {
            header: {
                class: Header,
                config: {
                    placeholder: "Entrez un titre",
                    levels: [2, 3, 4],
                    defaultLevel: 2
                }
            },
            image: {
                class: ImageTool,
                config: {
                    uploader: {
                        uploadByFile(file) {
                            let formData = new FormData();
                            formData.append('image', file);
                            return fetch("<?= Utils::getEnv()->getValue("PATH_SUBFOLDER")?>cmw-admin/pages/uploadImage/add", {
                                method: "POST",
                                body: formData
                            }).then(res => res.json())
                                .then(response => {
                                    return {
                                        success: 1,
                                        file: {
                                            url: "<?= Utils::getEnv()->getValue("PATH_URL")?>public/uploads/editor/" + response
                                        }
                                    }
                                })
                        }
                    }
                }
            },
            list: List,
            quote: {
                class: Quote,
                config: {
                    quotePlaceholder: "",
                    captionPlaceholder: "Auteur",
                },
            },
            warning: Warning,
            code: CodeTool,
            delimiter: Delimiter,
            table: Table,
            embed: {
                class: Embed,
                config: {
                    services: {
                        youtube: true,
                        coub: true
                    }
                }
            },
            Marker: Marker,
            underline: Underline,
        },
        defaultBlock: "paragraph",
        /**
         * Initial Editor data
         */
        data: <?= $article->getContentNotTranslate() ?>,
        onReady: function () {
            new Undo({editor});
            const undo = new Undo({editor});
            new DragDrop(editor);
        },
        onChange: function () {
        }
    });

    /**
     * Get url for auto categories
     */
    function getCurrentURL () {
      return window.location.href
    };
    const url = getCurrentURL();
    

    /**
     * Saving button
     */
    const saveButton = document.getElementById("saveButton");
    /**
     * Saving action
     */
    saveButton.addEventListener("click", function () {
        let isDefine = 0;
        if (document.getElementById("isDefine").checked) {
            isDefine = 1;
        }
        editor.save()
            .then((savedData) => {

                let formData = new FormData();
                formData.append('title', document.getElementById("title").value);
                formData.append('icon', document.getElementById("icon").value);
                formData.append('categorie', document.getElementById("categorie").value);
                formData.append('content', JSON.stringify(savedData));
                formData.append('isDefine', isDefine.toString());
                console.log (JSON.stringify(savedData))
                fetch(url, {
                    method: "POST",
                    body: formData
                })

                button.disabled = true;
                button.innerHTML = "<?= LangManager::translate('wiki.button.saving') ?>";
                setTimeout(() => {
                            button.innerHTML = "<i style='color: #16C329;' class='fa-solid fa-check fa-shake'></i> Ok !";
                        }, 850);
                setTimeout(() => {
                            document.location.replace("<?= Utils::getHttpProtocol() . '://' . $_SERVER['SERVER_NAME'] . getenv("PATH_SUBFOLDER") . 'cmw-admin/wiki/list'?>");
                        }, 1000);
                                
            })
            .catch((error) => {
                alert("Error : " + error);
            });
    });
</script>