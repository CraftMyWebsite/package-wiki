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

Pour accéder à votre WIKI faites ``monsite.fr/wiki``

> Version: `V1.0`


