<?php

define('DS', DIRECTORY_SEPARATOR);
date_default_timezone_set('America/Sao_Paulo');

$app = new \Phalcon\Mvc\Micro();
$app['db'] = function() {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host"     => "localhost",
        "username" => "root",
        "password" => "",
        "dbname"   => "class-prediction"
    ));
};

$app['view'] = function() {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir('..' . DS . 'src' . DS . 'View' . DS);
    $view->setLayout('index');
    return $view;
};

$app['session'] = function() {
    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
};

$app['flashSession'] = function() {
    $flash = new \Phalcon\Flash\Session(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );
    return $flash;
};

$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
    '..' . DS . 'src' . DS . 'Model' . DS
))->register();

$loader->registerNamespaces(array(
        'Application' => ".." . DS . "src" . DS,
));

$index = new \Phalcon\Mvc\Micro\Collection();
$index->setHandler('Application\Controller\IndexController', true);
$index->get('/', 'index');

$anosLetivos = new \Phalcon\Mvc\Micro\Collection();
$anosLetivos->setHandler('Application\Controller\AnosLetivosController', true);
$anosLetivos->setPrefix('/anos-letivos');
$anosLetivos->get('/', 'index');
$anosLetivos->get('/adicionar', 'adicionar');
$anosLetivos->post('/adicionar', 'adicionar');

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo '<center><h1>Desculpe, esta página não existe</h1></center>';
});


$app->mount($index);
$app->mount($anosLetivos);
$app->handle();