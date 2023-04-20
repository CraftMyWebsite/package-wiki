<!------------------------------------
    ----- Required namespace-----
-------------------------------------->
<?php use CMW\Utils\Utils; ?>

<!------------------------------------
           ----- Navigation-----
-------------------------------------->
<?php foreach ($categories as $categorie): ?>
    <i class="<?= $categorie->getIcon() ?>"></i> <?= $categorie->getName() ?></div>
        <?php foreach ($categorie?->getArticles() as $menuArticle): ?>
            <a href="<?= Utils::getEnv()->getValue("PATH_SUBFOLDER") . "wiki/" . $categorie->getSlug() . "/" . $menuArticle->getSlug() ?>">
                <i class="<?= $menuArticle->getIcon() ?>"></i> <?= $menuArticle->getTitle() ?></div>
            </a>
        <?php endforeach; ?>
<?php endforeach; ?>

<!------------------------------------
            ----- Article -----
-------------------------------------->
<?php if($article !== null): ?>
    <i class="<?= $article->getIcon() ?>"></i><?= $article->getTitle() ?>
    <?= $article->getContent() ?>
    <?= date("d/m/Y", strtotime($article->getDateCreate())) ?>
    <?= $article->getAuthor()->getPseudo() ?>
    <?= date("d/m/Y", strtotime($article->getDateUpdate())) ?>
<?php elseif($firstArticle === null && $article !== null): ?>
    You haven't started creating your Wiki yet!
<?php else: ?>
    <i class="<?= $firstArticle->getIcon() ?>"></i><?= $firstArticle->getTitle() ?>
    <?= $firstArticle->getContent() ?>
    <?= date("d/m/Y", strtotime($firstArticle->getDateCreate())) ?>
    <?= $firstArticle->getAuthor()->getPseudo() ?>
    <?= date("d/m/Y", strtotime($firstArticle->getDateUpdate())) ?>
<?php endif; ?>