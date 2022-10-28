# Package WIKI pour [CraftMyWebsite](https://craftmywebsite.fr)



## Exemple

Tout d'abord veuillez créer un fichier dans le dossier ```view```de votre thème ```wiki/main.view.php```

Voici un petit exemple pour afficher en liste vos catégories et vos articles.

```php
    <?php foreach ($categories as $category): ?>
        <ul>
            <li><?= $category->getName() ?></li>
            <?php foreach ($category->getArticles() as $article): ?>
                <ol><?= $article->getTitle() ?></ol>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
```

Si vous souhaitez afficher par défaut le premier article, vous pouvez utiliser la variable:
```php
/* @var ?\CMW\Entity\Wiki\WikiArticlesEntity $firstArticle */

$firstArticle

//Afficher le contenu du premier article:
$firstArticle->getContent();
```
(Variable nullable)


Pour accéder à votre WIKI faites ``monsite.fr/wiki``

> Version: `V1.0`


