<?php

use CMW\Manager\Env\EnvManager;
use CMW\Utils\Website;

/* @var \CMW\Entity\Wiki\WikiCategoriesEntity [] $categories */
/* @var \CMW\Entity\Wiki\WikiArticlesEntity $article */
/* @var \CMW\Entity\Wiki\WikiArticlesEntity $firstArticle */

Website::setTitle('Wiki');
Website::setDescription('Wiki');
?>
<section style="width: 70%;padding-bottom: 6rem;margin: 1rem auto auto;">

<div style="display: flex; flex-wrap: wrap; gap: 1rem;">
    <div style="flex: 0 0 25%; border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px; height: fit-content">
        <h4 style="text-align: center">Navigation</h4>
        <?php foreach ($categories as $categorie): ?>
            <b><i class="<?= $categorie->getIcon() ?>"></i><?= $categorie->getName() ?></b>
            <?php foreach ($categorie?->getArticles() as $menuArticle): ?>
                <a href="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'wiki/' . $categorie->getSlug() . '/' . $menuArticle->getSlug() ?>">
                    <div style="margin-left: 16px"><i class="<?= $menuArticle->getIcon() ?>"></i> <?= $menuArticle->getTitle() ?></div>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <div style="flex: 0 0 73%; border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px;">
        <?php if ($article !== null): ?>
            <h4 style="text-align: center"><i class="<?= $article->getIcon() ?>"></i> <?= $article->getTitle() ?></h4>
            <div><?= $article->getContent() ?></div>
            <div style="display:flex; justify-content: space-between">
                <div class="mt-1">Crée le : <?= $article->getDateCreate() ?></div>
                <div><?= $article->getAuthor()->getPseudo() ?></div>
                <div class="mt-1">Modifié le : <?= $article->getDateUpdate() ?></div>
            </div>
        <?php elseif (empty($firstArticle)): ?>
            <h4 style="text-align: center">Aucun article !</h4>
        <?php else: ?>
            <h4 style="text-align: center"><i class="<?= $firstArticle->getIcon() ?>"></i> <?= $firstArticle->getTitle() ?></h4>
            <div><?= $firstArticle->getContent() ?></div>
            <div style="display:flex; justify-content: space-between">
                <div class="mt-1">Crée le : <?= $firstArticle->getDateCreate() ?></div>
                <div><?= $firstArticle->getAuthor()->getPseudo() ?></div>
                <div class="mt-1">Modifié le : <?= $firstArticle->getDateUpdate() ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
</section>

