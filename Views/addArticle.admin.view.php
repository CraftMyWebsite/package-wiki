<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Utils\Website;

$title = LangManager::translate("wiki.title.dashboard_title");
$description = LangManager::translate("wiki.title.dashboard_desc");

?>
<div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("wiki.title.add_article") ?></h4>
            </div>
            <div class="card-body">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 ">

                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <h6><?= LangManager::translate("wiki.add.article_title") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" id="title" required
                                                       placeholder="<?= LangManager::translate("wiki.add.article_title_placeholder") ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-heading"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <h6><?= LangManager::translate("wiki.add.category_icon") ?> :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" id="icon" required
                                                       placeholder="<?= LangManager::translate("wiki.add.category_icon_placeholder") ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-icons"></i>
                                                </div>
                                                <small class="form-text"><?= LangManager::translate("wiki.add.hint_icon") ?> <a
                                                        href="https://fontawesome.com/search?o=r&m=free" target="_blank">FontAwesome.com</a></small>
                                            </div>
                                        </div>
                                    </div>
                                        <h6><?= LangManager::translate("wiki.add.article_content") ?> :</h6>
                                        <div class="card-in-card" id="editorjs"></div>
                                    <div class="text-center mt-2">
                                        <button disabled id="saveButton" type="submit" class="btn btn-primary"><?= LangManager::translate('wiki.button.create_before') ?></button>
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
                            return fetch("<?=  EnvManager::getInstance()->getValue("PATH_SUBFOLDER")?>cmw-admin/Pages/uploadImage/add", {
                                method: "POST",
                                body: formData
                            }).then(res => res.json())
                                .then(response => {
                                    return {
                                        success: 1,
                                        file: {
                                            url: "<?= EnvManager::getInstance()->getValue("PATH_SUBFOLDER")?>Public/Uploads/Editor/" + response
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
        data: {},
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
        editor.save()
            .then((savedData) => {

                let formData = new FormData();
                formData.append('title', document.getElementById("title").value);
                formData.append('icon', document.getElementById("icon").value);
                formData.append('content', JSON.stringify(savedData));

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
                            document.location.replace("<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . 'cmw-admin/wiki/list'?>");
                        }, 1000);
                                
            })
            .catch((error) => {
                alert("Error " + error);
            });
    });
</script>