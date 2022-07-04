<?php

use CMW\Controller\wiki\WikiController;
use CMW\Router\Router;

require_once('Lang/'.getenv("LOCALE").'.php');

/** @var $router Router Main router */

//Admin pages
$router->scope('/cmw-admin/wiki', function($router) {
    $router->get('/list', "wiki#frontWikiListAdmin");

    $router->get('/add/categorie', "wiki#addCategorie");
    $router->post('/add/categorie', "wiki#addCategoriePost");

    $router->get('/add/article', "wiki#addArticle");
    $router->post('/add/article', "wiki#addArticlePost");

    $router->get('/edit/categorie/:id', function($id) {
        (new WikiController)->editCategorie($id);
    })->with('id', '[0-9]+');
    $router->post('/edit/categorie/:id', function($id) {
        (new WikiController)->editCategoriePost($id);
    })->with('id', '[0-9]+');

    $router->get('/edit/article/:id', function($id) {
        (new WikiController)->editArticle($id);
    })->with('id', '[0-9]+');
    $router->post('/edit/article/:id', function($id) {
        (new WikiController)->editArticlePost($id);
    })->with('id', '[0-9]+');

    $router->get('/delete/article/:id', function($id) {
        (new WikiController)->deleteArticle($id);
    })->with('id', '[0-9]+');
    $router->get('/delete/categorie/:id', function($id) {
        (new WikiController)->deleteCategorie($id);
    })->with('id', '[0-9]+');

    $router->get('/define/categorie/:id', function($id) {
        (new WikiController)->defineCategorie($id);
    })->with('id', '[0-9]+');
    $router->get('/define/article/:id', function($id) {
        (new WikiController)->defineArticle($id);
    })->with('id', '[0-9]+');

});

$router->scope('/cmw-admin/wiki/list', function($router) {


//Public pages
$router->scope('/wiki', function ($router){
    //get all categories
    $router->get('/', "wiki#publicMain");


    $router->get('/:slugC/:slugA', function($slugC,$slugA) {
        (new WikiController)->publicShowArticle($slugC,$slugA);
    })->with('slug', '.*?');

});

});